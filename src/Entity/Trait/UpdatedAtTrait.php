<?php
// Cette class qui n'en ai pas une intégre la notion de 'trait' 
// Cette notion permet d'alléger le code en mutualisant les propriétés communes
// aux entités (elle ne peut pas être instanciée et n'a pas de méthode __construct)
namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;

trait UpdatedAtTrait
{    
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}