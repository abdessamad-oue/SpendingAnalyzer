<?php

namespace AO\AnalyzerBundle\Entity;

use Doctrine\ORM\Mapping as ORM; 

/**
 * @ORM\Entity(repositoryClass="AO\AnalyzerBundle\Entity\TransactionRepository")
 * @ORM\Table(name="transaction")
 */
class Transaction 
{
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
        
    /**
     * @ORM\Column(type="date")
     */
    private $date; 
    
    /**
     * @ORM\Column(type="float")
     */
    private $amount; 
    
    /**
     * @ORM\Column(type="text")
     */
    private $wording;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="transactions")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;
    
    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="transactions")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     */
    private $account;
    
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;
    
    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $modified_at; 
    
    
     /**
     * Constructor
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
               
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
     * Set date
     *
     * @param \DateTime $date
     * @return Category
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set amout
     *
     * @param integer $amout
     * @return Category
     */
    public function setAmout($amout)
    {
        $this->amout = $amout;
    
        return $this;
    }

    /**
     * Get amout
     *
     * @return integer 
     */
    public function getAmout()
    {
        return $this->amout;
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
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Category
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    
        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set modified_at
     *
     * @param \DateTime $modifiedAt
     * @return Category
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modified_at = $modifiedAt;
    
        return $this;
    }

    /**
     * Get modified_at
     *
     * @return \DateTime 
     */
    public function getModifiedAt()
    {
        return $this->modified_at;
    }

    /**
     * Set category
     *
     * @param \AO\AnalyzerBundle\Entity\Category $category
     * @return Transaction
     */
    public function setCategory(\AO\AnalyzerBundle\Entity\Category $category = null)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return \AO\AnalyzerBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return Transaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    
        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set account
     *
     * @param \AO\AnalyzerBundle\Entity\Account $account
     * @return Transaction
     */
    public function setAccount(\AO\AnalyzerBundle\Entity\Account $account = null)
    {
        $this->account = $account;
    
        return $this;
    }

    /**
     * Get account
     *
     * @return \AO\AnalyzerBundle\Entity\Account 
     */
    public function getAccount()
    {
        return $this->account;
    }
}