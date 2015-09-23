<?php

namespace AO\AnalyzerBundle\Entity; 

use Doctrine\ORM\Mapping as ORM; 

/**
 * @ORM\Entity(repositoryClass="AO\AnalyzerBundle\Entity\CategoryRepository")
 * @ORM\Table(name="category")
 */
class Category
{
     /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $code; 
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $label;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wording;

        
    /**
     * @ORM\OneToMany(targetEntity="Transaction", mappedBy="category")
     */
    private $transactions;
    
    
    public function __toString()
    {
        return $this->getLabel();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Category
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set wording
     *
     * @param string $wording
     * @return Category
     */
    public function setWording($wording)
    {
        $this->wording = $wording;
    
        return $this;
    }

    /**
     * Get wording
     *
     * @return string 
     */
    public function getWording()
    {
        return $this->wording;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->transactions = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add transactions
     *
     * @param \AO\AnalyzerBundle\Entity\Transaction $transactions
     * @return Category
     */
    public function addTransaction(\AO\AnalyzerBundle\Entity\Transaction $transactions)
    {
        $this->transactions[] = $transactions;
    
        return $this;
    }

    /**
     * Remove transactions
     *
     * @param \AO\AnalyzerBundle\Entity\Transaction $transactions
     */
    public function removeTransaction(\AO\AnalyzerBundle\Entity\Transaction $transactions)
    {
        $this->transactions->removeElement($transactions);
    }

    /**
     * Get transactions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * Set label
     *
     * @param string $label
     * @return Category
     */
    public function setLabel($label)
    {
        $this->label = $label;
    
        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }
}