<?php
/**
 * This file is part of the PHPOrchestra\CMSBundle.
 *
 * @author Noël Gilain <noel.gilain@businessdecision.com>
 */

namespace PHPOrchestra\CMSBundle\Controller\Block;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PHPOrchestra\CMSBundle\Model\Area;

class SampleController extends Controller
{
    
    /**
     * Render the sampleblock
     * 
     * @param array $elementsList array containing custom attributes
     * @param array $_page_parameters additional parameters extracted from url
     */
    public function showAction($title, $author, $news, $_page_parameters = array())
    {
        $datetime = time();
        
        $response = $this->render(
            'PHPOrchestraCMSBundle:Block/Sample:show.html.twig',
            array(
                  'title' => $title,
                  'author' => $author,
                  'news' => $news,
                  'parameters' => $_page_parameters,
                  'datetime' => $datetime
            )
        );
        
        $response->setPublic();
        $response->setSharedMaxAge(5);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        
        return $response;
    }
}
