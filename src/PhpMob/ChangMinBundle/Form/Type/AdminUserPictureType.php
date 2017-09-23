<?php

namespace PhpMob\ChangMinBundle\Form\Type;

use PhpMob\MediaBundle\Form\Type\ImageType;

class AdminUserPictureType extends ImageType
{
    /**
     * {@inheritdoc}
     */
    public function getFilterSection()
    {
        return 'admin_user_picture';
    }
}