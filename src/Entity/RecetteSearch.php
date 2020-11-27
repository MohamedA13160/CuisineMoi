<?php 


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class RecetteSearch{

    /**
     * @var string|null
     */
    
    private $nameRecette;

    /**
     * @var int|null
     */
    
    private $note;

    /**
     * @var ArrayCollection
     */

     private $options;

     public function __construct()
     {
         $this->options = new ArrayCollection();
     }
    /**
     * Get the value of nameRecette
     */ 
    public function getNameRecette()
    {
        return $this->nameRecette;
    }

    /**
     * Set the value of nameRecette
     *
     * @return  self
     */ 
    public function setNameRecette($nameRecette)
    {
        $this->nameRecette = $nameRecette;

        return $this;
    }

    /**
     * Get the value of note
     */ 
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set the value of note
     *
     * @return  self
     */ 
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

     /**
      * Get the value of options
      */ 
     public function getOptions()
     {
          return $this->options;
     }

     /**
      * Set the value of options
      *
      * @return  self
      */ 
     public function setOptions($options)
     {
          $this->options = $options;

          return $this;
     }
}
?>