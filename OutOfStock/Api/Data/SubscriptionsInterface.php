<?php

namespace Delovunity\OutOfStock\Api\Data;

/**
Interface SubscriptionsInterface
 @api
 */
interface SubscriptionsInterface
{
    /**#@+
     * Constants
     * @var string
     */
    const ID = 'id';
    const ID_PRODUCT = 'id_product';
    const EMAIL = 'email';
    const WEB_SITE = 'website';
    const ID_USER = 'id_user';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**
     * @param int $id_product
     * @return $this
     */
    public function setIdProduct(int $id_product);

    /**
     * @return int
     */
    public function getIdProduct();


    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param int $website
     * @return $this
     */
    public function setWebSite(int $website);

    /**
     * @return int
     */
    public function getWebSite();

    /**
     * @param int $id_user
     * @return $this
     */
    public function setIdUser(int $id_user);

    /**
     * @return int
     */
    public function getIdUser();

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt(string $createdAt);

    /**
     * @return string
     */
    public function getUpdatedAt();

    /**
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt(string $updatedAt);

    /**
     * @return int
     */
    public function getId();
}
