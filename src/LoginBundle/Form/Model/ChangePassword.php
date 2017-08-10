<?php

namespace LoginBundle\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePassword {

    /**
     * @SecurityAssert\UserPassword(
     *     message = "Wrong value for your current password"
     * )
     */
    protected $oldPassword;

    /**
     * @var string
     * @Assert\Length(
     *      min = 6,
     *      minMessage = "Your password must be at least {{ limit }} characters long",
     * )
     * @Assert\NotBlank(message="Password is required")
     */
    private $password;
    
    /**
     * Set oldPassword
     *
     * @param string $oldPassword
     * @return User
     */
    public function setoldPassword($oldPassword) {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    /**
     * Get oldPassword
     *
     * @return string 
     */
    public function getoldPassword() {
        return $this->oldPassword;
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
}
