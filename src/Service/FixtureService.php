<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class FixtureService
{
    private $fixture;
    private $entityManager;
    private $gameRepository;

    /**
     * FixtureService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->fixture = [];
        $this->entityManager = $entityManager;
        $this->gameRepository = $entityManager->getRepository(Game::class);
    }

    /**
     * @param array $items
     */
    private function rotateTeams(array &$items)
    {
        $itemCount = count($items);
        if ($itemCount < 3) {
            return;
        }
        $lastIndex = $itemCount - 1;

        $factor = (int)($itemCount % 2 === 0 ? $itemCount / 2 : ($itemCount / 2) + 1);
        $topRightIndex = $factor - 1;
        $topRightItem = $items[$topRightIndex];
        $bottomLeftIndex = $factor;
        $bottomLeftItem = $items[$bottomLeftIndex];
        for ($i = $topRightIndex; $i > 0; $i -= 1) {
            $items[$i] = $items[$i - 1];
        }
        for ($i = $bottomLeftIndex; $i < $lastIndex; $i += 1) {
            $items[$i] = $items[$i + 1];
        }
        $items[1] = $bottomLeftItem;
        $items[$lastIndex] = $topRightItem;
    }

    /**
     * @param int $weeks
     * @return FixtureService
     */
    public function generate(int $weeks = 34): self
    {
        if ($this->gameRepository->gamesCount() == 0) {
            $teamRepository = $this->entityManager->getRepository(Team::class);
            $teams = $teamRepository->findAll();

            $teamCount = count($teams);
            $halfTeamCount = $teamCount / 2;

            //Shuffle for different schedule
            shuffle($teams);

            for ($week = 1; $week <= $weeks; $week += 1) {
                foreach ($teams as $key => $team) {
                    if ($key >= $halfTeamCount) {
                        break;
                    }
                    $team1 = $team;
                    $team2 = $teams[$key + $halfTeamCount];

                    $game = new Game();
                    $game->setTime(new \DateTime());
                    $game->setWeek($week);

                    //Home-away swapping
                    if ($week % 2 === 0)
                        $game->setHomeTeam($team1)->setAwayTeam($team2);
                    else
                        $game->setHomeTeam($team2)->setAwayTeam($team1);

                    $this->entityManager->persist($game);
                }
                $this->rotateTeams($teams);
            }
            $this->entityManager->flush();
        }

        return $this;
    }

    /**
     * @param int $week
     * @return array
     */
    public function getFixture(int $week): array
    {
        if ($this->gameRepository->gamesCount() != 0) {
            $games = $this->gameRepository->findBy(['week' => $week], []);
            foreach ($games as $game) {
                $this->fixture[$week][] = $game;
            }
        }
        return $this->fixture;
    }

    /**
     * @param int $week
     */
    public function playFixture(int $week): void
    {
        //Checking for played weeks not playing again
        $games = $this->gameRepository->unPlayedGames($week);

        //Checking previous week games played
        if ($week == 1){
            $previousWeekState = true;
        }else {
            $previousWeek = $this->gameRepository->unPlayedGames($week - 1);
            if (count($previousWeek) == 0)
                $previousWeekState = true;
            else
                $previousWeekState = false;
        }

        if (count($games) != 0 && $previousWeekState) {
            foreach ($games as $game) {
                $game->setHomeScore(rand(0, 5));
                $game->setAwayScore(rand(0, 5));

                $homeGoalsFor = $game->getHomeTeam()->getGoalsFor() + $game->getHomeScore();
                $homeGoalsAgainst = $game->getHomeTeam()->getGoalsAgainst() + $game->getAwayScore();
                $homePoints = $game->getHomeTeam()->getPoints();

                $awayGoalsFor = $game->getAwayTeam()->getGoalsFor() + $game->getAwayScore();
                $awayGoalsAgainst = $game->getAwayTeam()->getGoalsAgainst() + $game->getHomeScore();
                $awayPoints = $game->getAwayTeam()->getPoints();

                //Home Team Won The Game
                if ($game->getHomeScore() > $game->getAwayScore()) {
                    $homeWonCount = $game->getHomeTeam()->getWon() + 1;
                    $homeGoalDifference = $game->getHomeTeam()->getGoalDifference() +
                        ($game->getHomeScore() - $game->getAwayScore());

                    //Home team update
                    $game->getHomeTeam()
                        ->setWon($homeWonCount)
                        ->setGoalsFor($homeGoalsFor)
                        ->setGoalsAgainst($homeGoalsAgainst)
                        ->setGoalDifference($homeGoalDifference)
                        ->setPlayed($week)
                        ->setPoints($homePoints + 3);

                    $awayLostCount = $game->getAwayTeam()->getLost() + 1;
                    $awayGoalDifference = $game->getAwayTeam()->getGoalDifference() -
                        ($game->getHomeScore() - $game->getAwayScore());

                    //Away team update
                    $game->getAwayTeam()
                        ->setLost($awayLostCount)
                        ->setGoalsFor($awayGoalsFor)
                        ->setGoalsAgainst($awayGoalsAgainst)
                        ->setGoalDifference($awayGoalDifference)
                        ->setPlayed($week);

                } else if ($game->getHomeScore() < $game->getAwayScore()) { //Away Team Won The Game

                    $homeLostCount = $game->getHomeTeam()->getLost() + 1;
                    $homeGoalDifference = $game->getHomeTeam()->getGoalDifference() -
                        ($game->getAwayScore() - $game->getHomeScore());

                    //Home team update
                    $game->getHomeTeam()
                        ->setPlayed($week)
                        ->setLost($homeLostCount)
                        ->setGoalsFor($homeGoalsFor)
                        ->setGoalsAgainst($homeGoalsAgainst)
                        ->setGoalDifference($homeGoalDifference);

                    $awayWonCount = $game->getAwayTeam()->getWon() + 1;
                    $awayGoalDifference = $game->getAwayTeam()->getGoalDifference() +
                        ($game->getAwayScore() - $game->getHomeScore());

                    //Away team update
                    $game->getAwayTeam()
                        ->setWon($awayWonCount)
                        ->setGoalsFor($awayGoalsFor)
                        ->setGoalsAgainst($awayGoalsAgainst)
                        ->setGoalDifference($awayGoalDifference)
                        ->setPlayed($week)
                        ->setPoints($awayPoints + 3);
                } else { //Drawn
                    $homeDrawnCount = $game->getHomeTeam()->getDrawn() + 1;
                    $awayDrawnCount = $game->getAwayTeam()->getDrawn() + 1;
                    $game->getHomeTeam()
                        ->setDrawn($homeDrawnCount)
                        ->setPlayed($week)
                        ->setGoalsFor($homeGoalsFor)
                        ->setGoalsAgainst($homeGoalsAgainst)
                        ->setPoints($homePoints + 1);

                    $game->getAwayTeam()
                        ->setDrawn($awayDrawnCount)
                        ->setPlayed($week)
                        ->setGoalsFor($awayGoalsFor)
                        ->setGoalsAgainst($awayGoalsAgainst)
                        ->setPoints($awayPoints + 1);
                }
                $this->entityManager->persist($game);
            }
            $this->entityManager->flush();
        }
    }
}