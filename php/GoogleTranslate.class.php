<?php

/*******************************************************************************
** Class: GoogleTranslate
** Purpose: Translate text using Google language tools
** Filename: GoogleTranslate.class.php
** Author: Raymond Mancy
** Author Email: raymond . mancy @ gmail . com
** Date: June 3 2006
** LICENSE:  This program is free software; you can redistribute it and/or ** modify it under the terms of the GNU General Public License as published ** by the Free Software Foundation; either version 2 of the License, or
** (at your option) any later version. This program is distributed in the ** hope that it will be useful, but WITHOUT ANY WARRANTY; without even the ** implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  ** See the GNU General Public License for more details.
********************************************************************************/


class GoogleTranslate{

    var $languages = NUll; //array to store valid languages
  
    var $validTranslation = NULL; //array to store valid lang-lang translation

    var $langFrom = NULL; //language converting from

    var $langTo = NULL;  //language conveting to

    var $text = NULL; //text to translate

    var $google_url = NULL; //where we get the service from

    var $post_data = NULL;
  
    function GoogleTranslate($langFrom, $langTo, $text) {

	$this->google_url = "http://www.google.es/translate_t";
	$this->langFrom = $langFrom;
	$this->langTo = $langTo;
	$this->text = $text; //text to translate to

 
        $this->languages= array ( //elligable languages (I've only included common Euro langs)
			         "en" => "english",
			         "de" => "german",
			         "fr" => "french",
			         "es" => "spanish",
			         "it"=>  "italian"
			        );

        $this->validTranslations =  array ( //these are the conversions allowed (I've only included 
				            //common Euro languages
				    
				           "en|de", //English to German
				           "en|fr", //English to French 
				           "en|it", //English to Italian 
				           "en|es", //English to Spanish
				    
				           "de|en", //German to English
				           "de|fr", //German to French
				    
				           "fr|en", //French to English
				           "fr|de", //French to German

					   "it|en", //Italian to English
   				    
				           "es|en", //Spanish to English
				    
				          ); 


        $post_data = array(
	              	     'langpair'=> NULL,
		             'text' => NULL
		           );

} //constructor
    



 /*returnLanguageTo() will return the lanaguage that has currently been set
  *to translate TO
  *preconditions:- contructor has been called
  *postconditions:- none
  *returns: - the language that has been set to translate to, and null if none have been set
  */
 function returnLanguageTo() {
   return $this->langTo;
 }

  /*returnLanguageFrom() will return the lanaguage that has currently been set
  *to translate FROM
  *preconditions:- contructor has been called
  *postconditions:- none
  *returns: - the language that has been set to translate FROM, and null if none have been set
  */
 function returnLanguageFrom() {
   return $this->langFrom;
 }






  /*setLanguageTo() will set the lanaguage to translate TO
  *
  *preconditions:- none
  *postconditions:- the language to be translated to will be set to $langT
  *returns: - returns -1 on failure if the language is not valid, else 1
  */
 function setLanguageTo($langT) {
   $this->langTo = $langT;
 }




  /*setLanguageFrom() will set the lanaguage to translate FROM
  *
  *preconditions:- none
  *postconditions:- the language to be translated FROM will be set to $langF
  *returns: - returns -1 on failure if the language is not valid, else 1
  */ 

 function setLanguageFrom($langF) {
     $this->langFrom = $langF;
 }


 /*setText() sets the text to be translated
  *preconditions: none
  *postconditions: sets the text to be translated to $text
  *returns: n/a
  */

 function setText($text) {
   $this->text = $text;
 } 


 function validateLang($langPair) {
   
   if (!in_array($langPair,$this->validTranslations)) {
    return -1;
  }
   return 1;
 }




 /*translate will take the language from, language to, and text variable already initialised
  * and return the translated text
  *preconditions:- $this->languageFrom, $this->languageTo, $this->text have already been
  *initialised
  *postcoditions:-none
  *returns:- the translated text
  */
 function translate() {

     $i = $this->langFrom."|".$this->langTo;
   
     if ($this->validateLang($i) < 0 )
         die ("Error, could not translate");
   
   
     $this->post_data['langpair'] = $i;
     $query = $this->buildQuery();
   
   
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, $this->google_url); 
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_POST, 1);
     curl_setopt($ch, CURLOPT_POSTFIELDS, $query);

     $output = curl_exec($ch);
     $processedOutput = $this->filterOutput($output);
   
     return $processedOutput;
   
 } //translate()

 /*processOutput() takes the source page of a request to google translate and filters it for
  * the translated words
  *preconditions:- $output is valid source from googles translate service
  *postconditions:- none
  *returns:- the translated words we are looking for
  */

 function filterOutput($output) {
   
   $search_regex='/<div id=result_box dir=ltr>[^<]*<\/div>/';

   
   $result = preg_match($search_regex,$output,$match);
   $match[0] = str_replace("&nbsp;","",$match[0]); //this gets rid of the HTML no break spaces

   return strip_tags($match[0]);
   
 } //filterOutput()



 /*buildQuery() will take the already initialised values of languageFrom, languageTo and text
  *to be translated, and puts them into a format which can be submitted as a request
  *to the google translating service
  */
 function buildQuery() {

   if (($this->langTo || $this->langFrom || $this-> text) == ("" || NULL))
     die("Please set language to, language from, and the text to be translated");
   
   $this->post_data['text']= $this->text; //set the text of what we want to translate
   
   return http_build_query($this->post_data); //create proper HTML query
 }//buildQuery()
 

} //GoogleTranslate


   




			 
