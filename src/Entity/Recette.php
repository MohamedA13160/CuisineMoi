<?php

namespace App\Entity;

use DateTime;
use App\Entity\User;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;

use App\Repository\RecetteRepository;

use Doctrine\Common\Collections\Collection;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=RecetteRepository::class)
 * @UniqueEntity("title")
 * @Vich\Uploadable
 * @ApiResource(
 *      normalizationContext = {"groups" = {"read:recette"} },
 *      collectionOperations = {"get"},
 *      itemOperations = {"get"}
 * )
 */
  
 

class Recette
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:recette"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\Length(
     *      min = 5,
     *      max = 255,
     *      minMessage = "Votre titre dois contenir au moins {{ limit }} charactères",
     *      maxMessage = "Votre titre dois contenir au plus {{ limit }} charactères",
     *      allowEmptyString = false
     * )
     * @Groups({"read:recette"})
     * 
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      min = 10,
     *      max = 255,
     *      minMessage = "Votre descriptions dois contenir au moins {{ limit }} charactères",
     *      maxMessage = "Votre descriptions dois contenir au plus {{ limit }} charactères",
     *      allowEmptyString = false
     * )
     * @Groups({"read:recette"})
     */
    private $description;


    /**
     * @ORM\Column(type="text")
     * @Groups("{read:recette}")
     */
    private $preparation;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read:recette"})
     */
    private $personne;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read:recette"})
     */
    
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="recettes")
     * @Groups({"read:recette"})
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="recettes")
     * @Groups({"read:recette"})
     */
    private $autheur;


    /**
     * @Vich\UploadableField(mapping="recettes_image", fileNameProperty="imageName")
     * @Groups({"read:recette"})
     * 
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read:recette"})
     */
    private $imageName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"read:recette"})
     * 
     * @var \DateTimeInterface|Null
     */
    private $updatedAt;


    /**
     * @ORM\Column(type="string", length=5)
     * @Groups({"read:recette"})
     */
    private $tps_cuisson;

    /**
     * @ORM\Column(type="string", length=5)
     * @Groups({"read:recette"}) 
     */
    private $tps_preparation;

    /**
     * @ORM\Column(type="text")
     * @Groups({"read:recette"})
     */
    private $ingredient;

    /**
     * @ORM\ManyToMany(targetEntity=Option::class, inversedBy="recettes")
     * @Groups({"read:recette"}))
     */

    private $options;


    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->ingredients = new ArrayCollection();
        $this->options = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPreparation(): ?string
    {
        return $this->preparation;
    }

    public function setPreparation(string $preparation): self
    {
        $this->preparation = $preparation;

        return $this;
    }

  

    public function getPersonne(): ?int
    {
        return $this->personne;
    }

    public function setPersonne(int $personne): self
    {
        $this->personne = $personne;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getSlug()
    {
      return $slugify = (new Slugify())->slugify($this->title);
        
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAutheur(): ?User
    {
        return $this->autheur;
    }

    public function setAutheur(?user $autheur): self
    {
        $this->autheur = $autheur;

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setRecette($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getRecette() === $this) {
                $commentaire->setRecette(null);
            }
        }

        return $this;
    }
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }
    
    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updateAt = $updatedAt;

        return $this;
    }

  

    public function getTpsCuisson(): ?string
    {
        return $this->tps_cuisson;
    }

    public function setTpsCuisson(string $tps_cuisson): self
    {
        $this->tps_cuisson = $tps_cuisson;

        return $this;
    }

    public function getTpsPreparation(): ?string
    {
        return $this->tps_preparation;
    }

    public function setTpsPreparation(string $tps_preparation): self
    {
        $this->tps_preparation = $tps_preparation;

        return $this;
    }

    public function getIngredient(): ?string
    {
        return $this->ingredient;
    }

    public function setIngredient(string $ingredient): self
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    /**
     * @return Collection|Option[]
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->addRecette($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->removeElement($option)) {
            $option->removeRecette($this);
        }

        return $this;
    }


 

}
