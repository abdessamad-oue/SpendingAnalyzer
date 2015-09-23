<?php

namespace AO\AnalyzerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="AO\AnalyzerBundle\Entity\AccountRepository")
 * @ORM\Table(name="account")
 * @ORM\HasLifecycleCallbacks()
 */
class Account
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min=6)
     * @Assert\Regex(
     *     pattern="/\s/",
     *     match=false,
     *     message="Le code ne doit pas contenir d'espace "
     * )
     * 
     */
    protected $code;
    
    
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */ 
    protected $comment;
    
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     * 
     */
    protected $main;

    /**
     * @ORM\OneToMany(targetEntity="Transaction", mappedBy="account")
     */
    protected $transactions;
    
    /**
     * @ORM\ManyToOne(targetEntity="AccountType", inversedBy="accounts")
     * @ORM\JoinColumn(name="account_type_id", referencedColumnName="id")
     */
    protected $accountType;


    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modified_at; 
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->transactions = new \Doctrine\Common\Collections\ArrayCollection();
        
        
    }
    
    
    public function __toString()
    {
        return $this->code . ' - ' . $this->comment;
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setModifiedAt(new \Datetime());
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
     * @return Account
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
     * Add transactions
     *
     * @param \AO\AnalyzerBundle\Entity\Transaction $transactions
     * @return Account
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
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Account
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
     * @return Account
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
     * Set comment
     *
     * @param string $comment
     * @return Account
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    
        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set main
     *
     * @param boolean $main
     * @return Account
     */
    public function setMain($main)
    {
        $this->main = $main;
    
        return $this;
    }

    /**
     * Get main
     *
     * @return boolean 
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * Set accountType
     *
     * @param \AO\AnalyzerBundle\Entity\AccountType $accountType
     * @return Account
     */
    public function setAccountType(\AO\AnalyzerBundle\Entity\AccountType $accountType = null)
    {
        $this->accountType = $accountType;
    
        return $this;
    }

    /**
     * Get accountType
     *
     * @return \AO\AnalyzerBundle\Entity\AccountType 
     */
    public function getAccountType()
    {
        return $this->accountType;
    }
}