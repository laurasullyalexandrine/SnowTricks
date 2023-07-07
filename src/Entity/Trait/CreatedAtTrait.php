<?php
// Cette class qui n'en ai pas une intégre la notion de 'trait' 
// Cette notion permet d'alléger le code en mutualisant les propriétés communes
// aux entités (elle ne peut pas être instanciée et n'a pas de méthode __construct)
namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;

trait CreatedAtTrait
{
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}