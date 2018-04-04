<?php
/**
 * Created by PhpStorm.
 * User: gaetan
 * Date: 20/03/2018
 * Time: 09:27
 */

namespace App\Component\IO;


class PersonnageIO
{
    private $nom;
    private $description;
    private $annee_naissance;
    private $annee_mort;

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getAnneeNaissance()
    {
        return $this->annee_naissance;
    }

    /**
     * @param mixed $annee_naissance
     */
    public function setAnneeNaissance($annee_naissance)
    {
        $this->annee_naissance = $annee_naissance;
    }

    /**
     * @return mixed
     */
    public function getAnneeMort()
    {
        return $this->annee_mort;
    }

    /**
     * @param mixed $annee_mort
     */
    public function setAnneeMort($annee_mort)
    {
        $this->annee_mort = $annee_mort;
    }



}