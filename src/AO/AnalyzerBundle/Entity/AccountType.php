<?php

namespace AO\AnalyzerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity()
 * @ORM\Table(name="account_type")
 * @ORM\HasLifecycleCallbacks()
 */
class AccountType
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
     */
    protected $code;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $label;
    
    /**
     * @ORM\OneToMany(targetEntity="Account", mappedBy="account_type")
     */
    protected $accounts;

    
    public function __toString()
    {
        return $this->getCode();
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
     * @return AccountType
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
     * Set label
     *
     * @param string $label
     * @return AccountType
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->accounts = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add accounts
     *
     * @param \AO\AnalyzerBundle\Entity\Account $accounts
     * @return AccountType
     */
    public function addAccount(\AO\AnalyzerBundle\Entity\Account $accounts)
    {
        $this->accounts[] = $accounts;
    
        return $this;
    }

    /**
     * Remove accounts
     *
     * @param \AO\AnalyzerBundle\Entity\Account $accounts
     */
    public function removeAccount(\AO\AnalyzerBundle\Entity\Account $accounts)
    {
        $this->accounts->removeElement($accounts);
    }

    /**
     * Get accounts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAccounts()
    {
        return $this->accounts;
    }
}