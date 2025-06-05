<?php

namespace App\DataFixtures;

use App\Entity\Food;
use App\Entity\FoodGroup;
use App\Entity\FoodSubGroup;
use App\Entity\FoodSubSubGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use SplFileObject;

class AppFixtures extends Fixture
{
    private const CSV = __DIR__ . '/../../var/import/Table_Ciqual_2020.csv';

    /** caches pour éviter les doublons */
    private array $groupCache    = [];
    private array $subGroupCache = [];
    private array $subSubCache   = [];

    public function load(ObjectManager $em): void
    {
        $csv = new SplFileObject(self::CSV);
        $csv->setFlags(SplFileObject::READ_CSV);
        $csv->setCsvControl(',');

        /* -------- repérage des colonnes nutrition -------- */
        $headers       = $csv->fgetcsv();
        $kcalIdx       = array_search('Energie, Règlement UE N° 1169/2011 (kcal/100 g)', $headers);
        $protIdx       = array_search('Protéines, N x facteur de Jones (g/100 g)',       $headers);
        $carbIdx       = array_search('Glucides (g/100 g)',                                $headers);
        $lipIdx        = array_search('Lipides (g/100 g)',                                 $headers);

        while (!$csv->eof()) {
            $row = $csv->fgetcsv();
            if ($row === [null] || $row === false) {
                continue;
            }

            [
                $gCode,
                $sgCode,
                $ssgCode,
                $gName,
                $sgName,
                $ssgName
            ] = array_map('trim', array_slice($row, 0, 6));

            /* ------------ FoodGroup ------------ */
            $group = $this->groupCache[$gCode] ??= $this->getOrCreateGroup($em, $gCode, $gName);

            /* ------------ FoodSubGroup ------------ */
            $subKey = $sgCode;
            $sub    = $this->subGroupCache[$subKey]
                ??= $this->getOrCreateSubGroup($em, $sgCode, $sgName, $group);

            /* ------------ FoodSubSubGroup ------------ */
            $ssKey  = $ssgCode;
            $subsub = $this->subSubCache[$ssKey]
                ??= $this->getOrCreateSubSubGroup($em, $ssgCode, $ssgName, $sub);

            /* ------------ Food ------------ */
            $food = (new Food())
                ->setCode((int) $row[6])
                ->setName($row[7])
                ->setNameSci($row[8] ?: null)
                ->setSubSubGroup($subsub)
                ->setEnergyKcal($this->num($row[$kcalIdx]))
                ->setProteins($this->num($row[$protIdx]))
                ->setCarbohydrates($this->num($row[$carbIdx]))
                ->setLipids($this->num($row[$lipIdx]));

            $em->persist($food);
        }

        $em->flush();           // un seul flush à la fin
    }

    /* ---------- helpers ---------- */

    private function getOrCreateGroup(ObjectManager $em, string $code, string $name): FoodGroup
    {
        return $em->getRepository(FoodGroup::class)->findOneBy(['code' => $code])
            ?? (function () use ($em, $code, $name) {
                $g = (new FoodGroup())->setCode($code)->setName($name);
                $em->persist($g);      // pas de flush : reste en UoW ⇒ réutilisable
                return $g;
            })();
    }

    private function getOrCreateSubGroup(ObjectManager $em, string $code, string $name, FoodGroup $group): FoodSubGroup
    {
        return $em->getRepository(FoodSubGroup::class)->findOneBy(['code' => $code])
            ?? (function () use ($em, $code, $name, $group) {
                $sg = (new FoodSubGroup())->setCode($code)->setName($name)->setFoodGroup($group);
                $em->persist($sg);
                return $sg;
            })();
    }

    private function getOrCreateSubSubGroup(ObjectManager $em, string $code, string $name, FoodSubGroup $sub): FoodSubSubGroup
    {
        return $em->getRepository(FoodSubSubGroup::class)->findOneBy(['code' => $code])
            ?? (function () use ($em, $code, $name, $sub) {
                $ssg = (new FoodSubSubGroup())->setCode($code)->setName($name)->setFoodSubGroup($sub);
                $em->persist($ssg);
                return $ssg;
            })();
    }

    private function num(string $raw): ?float
    {
        $raw = trim($raw);
        if ($raw === '' || $raw === '-') {
            return null;
        }
        return (float) str_replace([',', '<', '>'], ['.', '', ''], $raw);
    }
}
