<?php
/**
 * Created by PhpStorm.
 * User: kchapple
 * Date: 3/3/16
 * Time: 1:28 PM
 */

class Form implements TaggableInterface, FilterableInterface
{
    public function getName()
    {
        return 'form';
    }

    public function addTag( Tag $tag )
    {

    }

    public function removeTag( Tag $tag )
    {

    }
}
