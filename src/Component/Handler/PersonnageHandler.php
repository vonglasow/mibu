<?php

namespace App\Component\Handler;

use App\Entity\Element\Personnage;

class PersonnageHandler extends BaseHandler
{
    /**
     * @param $fictionId
     * @param $limit
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function generatePersonnages($fictionId, $limit)
    {
        if($limit > 1000) {
            $limit = 1000;
        }

        $personnage = new Personnage('Original', 'Le personnage original');

        for($i= 0; $i < $limit; $i++) {
            $clone = clone $personnage;

            $clone->setTitre('Clone n°'.($i+1));
            $clone->setDescription('Un clone');
            $clone->setPrenom($this->generatePrenomAtalaire());
            $clone->setNom($this->generateNomAtalaire());

            $genre = (rand(0,1)>0) ?$genre = 'M' :$genre = 'F';
            $clone->setGenre($genre);

            $clone->setAuto(TRUE);
            $clone->setFiction($this->getFiction($fictionId));

            $this->save($clone);
        }

        return true;

    }

    /**
     * @return array|string
     */
    public function generatePrenomAtalaire()
    {
        // calculer le nombre de syllabes
        $rand = rand(1,100);

        if($rand < 10) {
            $nbSyllables = 1;
        }

        else if($rand > 10 && $rand <70) {
            $nbSyllables = 2;
        }

        else if ($rand > 70 && $rand <90) {
            $nbSyllables = 3;
        }

        else {
            $nbSyllables = 4;
        }

        // liste des syllabes
        $syllables = ['ba', 'rius', 'a', 'ta', 'lai', 're', 'da', 'mu', 'ni','no','so', 'mo', 'do', 'lne', 'sa'];

        // assemblage des syllabes
        for ($i = 0; $i < $nbSyllables; $i++) {
            $prenom[] = $syllables[array_rand($syllables, 1)];
        }

        // créer le prénom
        $prenom = ucfirst(implode($prenom));

        return $prenom;
    }

    public function generateNomAtalaire()
    {
        //nb de syllabe 1 à 3
        // assemblage de syllabe
        $prenom = 'Atalaire';

        return $prenom;
    }
}