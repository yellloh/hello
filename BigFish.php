<?php
/** BigFish Sitemap generator
 *
 *  This class is a lightweight (!) sitemap generator for PHP. Written for one purpose - making Sitemaps!
 *  The code is released under the  MIT License.
 *
 *  Produced by A. I. Grayson-Widarsito (Yellloh) 2013
 *
 *  Have fun ;)
 *
 */

namespace Yellloh;

class BigFishException extends \Exception { }

class BigFish{

    const CHANGE_ALWAYS = 'always';
    const CHANGE_HOURLY = 'hourly';
    const CHANGE_DAILY = 'daily';
    const CHANGE_WEEKLY = 'weekly';
    const CHANGE_MONTHLY = 'monthly';
    const CHANGE_YEARLY = 'yearly';
    const CHANGE_NEVER = 'never';

    protected $XML = NULL;
    protected $Domain = NULL;
    protected $URLs = array();

    public function __construct(){
        $this->XML = new \SimpleXMLElement('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');
    }

    public function setDomain($DomainStr){
        $this->Domain = $DomainStr;
    }

    public function addURL($URI, $eChangeFreq = NULL, $Priority = NULL, $LastModified = NULL){
        if ($this->Domain == NULL){
            throw new BigFishException("You must set the 'Domain' field of this class before adding a URL.");
        }
        $this->URLs[$URI] = array(
            "changefreq" => $eChangeFreq,
            "priority" => $Priority,
            "lastmod" => $LastModified
        );
        return TRUE;
    }

    public function output(){
        foreach($this->URLs as $URI => $Properties){
            $URLNode = $this->XML->addChild("url");
            $URLNode->addChild("loc", $URI);

            foreach(array_keys($Properties) as $Key){
                if ($Properties[$Key] !== NULL){
                    $URLNode->addChild($Key, $Properties[$Key]);
                }
            }
        }
        $Dom = dom_import_simplexml($this->XML)->ownerDocument;
        $Dom->formatOutput = true;
        $Dom->encoding = "UTF-8";
        return $Dom->saveXML();
    }
}