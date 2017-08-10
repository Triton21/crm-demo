<?php

namespace LoginBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="LoginBundle\Entity\UserRepository")
 * @UniqueEntity("email", message="That email is already taken")
 * @UniqueEntity("username", message="Username is already taken")
 */
class User implements AdvancedUserInterface, \Serializable {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     * @Assert\NotBlank(message="You must have a username")
     * @Assert\Regex("/^[a-zA-z0-9_.\-]+$/", message="Username should be better :)" )
     */
    private $username;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="json_array")
     */
    private $roles;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isAdmin", type="boolean")
     */
    private $isAdmin;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     * @Assert\NotBlank(message="You must have a First Name")
     * @Assert\Regex("/^[a-zA-z0-9_.\-]+$/", message="First name too short" )
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     * @Assert\NotBlank(message="You must have a Last Name")
     * @Assert\Regex("/^[a-zA-z0-9_.\-]+$/", message="Last Name too short" )
     */
    private $lastname;
    
    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     * @Assert\NotBlank(message="You must have a Last Name")
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank(message="Email is required")
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="gdc", type="integer", nullable=true)
     * @Assert\NotBlank(message="GDC number is required")
     * @Assert\Type(type="integer",
     *     message="The value {{ value }} is not a valid number.")
     * @Assert\GreaterThan(0)
     */
    private $gdc;

    /**
     * @var string
     *
     * @ORM\Column(name="practice", type="string", length=255, nullable=true)
     */
    private $practice;
    
    /**
     * @var string
     *
     * @ORM\Column(name="token", type="text", nullable=true)
     */
    private $token;
    
    /**
     * @var string
     *
     * @ORM\Column(name="reset", type="text", nullable=true)
     */
    private $reset;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\NotBlank(message="Password is required")
     */
    private $password;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname) {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname() {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname) {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname() {
        return $this->lastname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword() {
        return $this->password;
    }

    public function getSalt() {
        return null;
    }

    public function eraseCredentials() {
        
    }

    /**
     * Returns the roles granted to the user.
     * @return Role[] The user roles
     */
    public function getRoles() {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    /**
     * Set roles
     *
     * @param array $roles
     * @return User
     */
    public function setRoles($roles) {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Set isAdmin
     *
     * @param array $isAdmin
     * @return User
     */
    public function setIsAdmin($isAdmin) {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * Get isAdmin
     *
     * @return array 
     */
    public function getIsAdmin() {
        return $this->isAdmin;
    }

    /**
     * Set gdc
     *
     * @param integer $gdc
     *
     * @return User
     */
    public function setGdc($gdc) {
        $this->gdc = $gdc;

        return $this;
    }

    /**
     * Get gdc
     *
     * @return integer
     */
    public function getGdc() {
        return $this->gdc;
    }

    /**
     * Set practice
     *
     * @param string $practice
     *
     * @return User
     */
    public function setPractice($practice) {
        $this->practice = $practice;

        return $this;
    }

    /**
     * Get practice
     *
     * @return string
     */
    public function getPractice() {
        return $this->practice;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return User
     */
    public function setAddress($address) {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive) {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive() {
        return $this->isActive;
    }

    public function isAccountNonExpired() {
        return true;
    }

    public function isAccountNonLocked() {
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }

    public function isEnabled() {
        return $this->isActive;
    }

    // serialize and unserialize must be updated - see below
    public function serialize() {
        return serialize(array(
            // ...
            $this->id,
            $this->username,
            $this->roles,
            $this->isAdmin,
            $this->firstname,
            $this->lastname,
            $this->email,
            $this->gdc,
            $this->practice,
            $this->address,
            $this->isActive,
        ));
    }

    public function unserialize($serialized) {
        list (
                // ...
                $this->id,
                $this->username,
                $this->roles,
                $this->isAdmin,
                $this->firstname,
                $this->lastname,
                $this->email,
                $this->gdc,
                $this->practice,
                $this->address,
                $this->isActive,
                ) = unserialize($serialized);
    }


    /**
     * Set token
     *
     * @param string $token
     *
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set reset
     *
     * @param string $reset
     *
     * @return User
     */
    public function setReset($reset)
    {
        $this->reset = $reset;

        return $this;
    }

    /**
     * Get reset
     *
     * @return string
     */
    public function getReset()
    {
        return $this->reset;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }
}
