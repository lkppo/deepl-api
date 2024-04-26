<?php
/**
 * DeepL API client library for PHP
 *
 * Before being independent, this library was written
 * for the WIKINDX project, a Bibliographic Management system.
 *
 * @package     Library
 * @author      Stéphane Aulery
 * @version     0.1.0
 * @copyright   Copyright (c) 2022-2024, LkpPo
 * @license https://www.isc.org/licenses/ ISC License
 * @see https://github.com/lkppo/deepl-api
 * @see https://wikindx.sourceforge.io/ The WIKINDX SourceForge project
 */

/**
 * DeepL client library for PHP
 *
 * @package LkpPo\DeepL
 */
namespace LkpPo\DeepL
{
	/**
	 * Client class for the DeepL v2 API
	 */
	class Deepl2
	{
		/**
		 * API key
		 */
		private string $auth_key = "";
		
		/**
		 * API URL (deduced from the API key)
		 */
		private string $url_api = "";
		
		/**
		 * Object initializer
		 *
		 * Memorize the API key and configure the URL automatically
		 *
		 * @param string $auth_key
		 */
		public function __construct(string $auth_key)
		{
			$this->auth_key = $auth_key;
			
			if (mb_substr($this->auth_key, -3) == ":fx")
				$this->url_api = "https://api-free.deepl.com/v2/";
			else
				$this->url_api = "https://api.deepl.com/v2/";
		}
		
		/**
		 * Object Destructor
		 */
		public function __destruct()
		{
			
		}
		
		
		///
		/// Other Functions
		////////////////////////////////////////////////////////////////
		
		/**
		 * Monitoring usage
		 *
		 * This allows you to monitor how much you translate, as well as the limits set.
		 *
		 * Implements POST /usage
		 *
		 * @see https://www.deepl.com/en/docs-api/other-functions/
		 *
		 * @return array
		 */
		public function getUsage()
		{
			$aResponse = $this->__curl_get("usage");
			
            $response = @json_decode($aResponse["response"], TRUE);
            if (json_last_error() != JSON_ERROR_NONE)
                $aResponse["response"] = [];
			else
                $aResponse["response"] = $response;
            
            return $aResponse;
		}
		
		/**
		 * Listing supported source languages
		 *
		 * This allows you to list all supported source languages of the API.
		 *
		 * Implements POST /languages with 'type=source' parameter
		 *
		 * @see https://www.deepl.com/en/docs-api/other-functions/
		 *
		 * @return array
		 */
		public function getSourceLanguages()
		{
			$aResponse = $this->__curl_post("languages", ["type" => "source"]);
			
            $response = @json_decode($aResponse["response"], TRUE);
            if (json_last_error() != JSON_ERROR_NONE)
                $aResponse["response"] = [];
			else
                $aResponse["response"] = $response;
            
            return $aResponse;
		}
		
		/**
		 * Listing supported target languages
		 *
		 * This allows you to list all supported target languages of the API.
		 *
		 * Implements POST /languages with 'type=target' parameter
		 *
		 * @see https://www.deepl.com/en/docs-api/other-functions/
		 *
		 * @return array
		 */
		public function getTargetLanguages()
		{
			$aResponse = $this->__curl_post("languages", ["type" => "target"]);
			
            $response = @json_decode($aResponse["response"], TRUE);
            if (json_last_error() != JSON_ERROR_NONE)
                $aResponse["response"] = [];
			else
                $aResponse["response"] = $response;
            
            return $aResponse;
		}
		
		
		///
		/// Managing Glossaries Functions
		////////////////////////////////////////////////////////////////
		
		/**
		 * List language pairs supported by glossaries
		 *
		 * The function for listing all language pairs supported by the glossary function returns a JSON object
		 * containing an array of source_lang and target_lang pair objects.
		 *
		 * Implements GET /glossary-language-pairs
		 *
		 * NB: POST is not working (2022-05-05)
		 *
		 * @see https://www.deepl.com/en/docs-api/managing-glossaries/
		 *
		 * @return array
		 */
		public function getGlossariesLanguages()
		{
			$aResponse = $this->__curl_get("glossary-language-pairs", []);
			
            $response = @json_decode($aResponse["response"], TRUE);
            if (json_last_error() != JSON_ERROR_NONE)
                $aResponse["response"] = [];
			else
                $aResponse["response"] = $response;
            
            return $aResponse;
		}
		
		/**
		 * Creating a glossary
		 *
		 * The function for creating a glossary returns a JSON object containing the ID of the newly created glossary
		 * and a boolean flag that indicates if the created glossary can already be used in translate requests.
		 *
		 * Implements POST /glossaries
		 *
		 * @see https://www.deepl.com/en/docs-api/managing-glossaries/
		 *
		 * @return array
		 */
		public function createGlossary(
		    string $name,
		    string $source_lang,
		    string $target_lang,
		    string $entries_format,
		    string $entries
		){
			$aResponse = $this->__curl_post(
			    "glossaries",
			    [
			        "name" => $name,
			        "source_lang" => $source_lang,
			        "target_lang" => $target_lang,
			        "entries_format" => $entries_format,
			        "entries" => $entries,
			    ]
			);
			
            
            $response = @json_decode($aResponse["response"], TRUE);
            if (json_last_error() != JSON_ERROR_NONE)
                $aResponse["response"] = [];
			else
                $aResponse["response"] = $response;
            
            return $aResponse;
		}
		
		/**
		 * List glossaries
		 *
		 * The function for listing glossaries returns a JSON object containing a list of all glossaries
		 * and their meta-information, omitting the glossary entries.
		 *
		 * Implements GET /glossaries
		 *
		 * @see https://www.deepl.com/en/docs-api/managing-glossaries/
		 *
		 * @return array
		 */
		public function listGlossaries()
		{
			$aResponse = $this->__curl_get("glossaries");
			
            $response = @json_decode($aResponse["response"], TRUE);
            if (json_last_error() != JSON_ERROR_NONE)
                $aResponse["response"] = [];
			else
                $aResponse["response"] = $response;
            
            return $aResponse;
		}
		
		/**
		 * Listing glossary information
		 *
		 * The function for listing glossary information returns a JSON object
		 * containing the meta-information for a single glossary, omitting the glossary entries.
		 *
		 * Implements GET /glossaries/[glossary_id]
		 *
		 * @see https://www.deepl.com/en/docs-api/managing-glossaries/
		 *
		 * @return array
		 */
		public function getGlossaryDetails(string $glossary_id)
		{
			$aResponse = $this->__curl_get("glossaries/$glossary_id");
			
            $response = @json_decode($aResponse["response"], TRUE);
            if (json_last_error() != JSON_ERROR_NONE)
                $aResponse["response"] = [];
			else
                $aResponse["response"] = $response;
            
            return $aResponse;
		}
		
		/**
		 * Get entries of one glossary
		 *
		 * The function for listing entries of a glossary returns the entries of a glossary in the requested format.
		 * The format can be specified by setting the Accept header.
		 *
		 * Implements GET /glossaries/[glossary_id]/entries
		 *
		 * @see https://www.deepl.com/en/docs-api/managing-glossaries/
		 *
		 * @return array
		 */
		public function getGlossary(string $glossary_id)
		{
			$aResponse = $this->__curl_get("glossaries/$glossary_id/entries", ["Accept: text/tab-separated-values"]);
			
            return $aResponse;
		}
		
		/**
		 * Delete a glossary
		 *
		 * The function for deleting a glossary deletes the specified glossary.
		 * It returns no content if the deletion was successful.
		 *
		 * Implements DELETE /glossaries/[glossary_id]
		 *
		 * @see https://www.deepl.com/en/docs-api/managing-glossaries/
		 *
		 * @return array
		 */
		public function deleteGlossary(string $glossary_id)
		{
			$aResponse = $this->__curl_delete("glossaries/$glossary_id");
			
            return $aResponse;
		}
		
		
		///
		/// Translating Text Functions
		////////////////////////////////////////////////////////////////
		
		/**
		 * Translating text (synchronous)
		 *
		 * This function allows to translate texts.
		 *
		 * Implements POST /translate
		 *
		 * @return array
		 */
		public function translateText(
		    $text,
		    $source_lang = "EN",
		    $target_lang = "",
		    $split_sentences = "1",
		    $preserve_formatting = "0",
		    $formality = "default",
		    $glossary_id = "",
		    $tag_handling = "",
		    $non_splitting_tags = "",
		    $outline_detection = "",
		    $splitting_tags = "",
		    $ignore_tags = ""
		)
		{
		    // Required params
		    $aParams = [
		        "text" => $text,
		        "target_lang" => trim($target_lang),
		    ];
		    
		    // Optional params
		    if (trim($source_lang) != "")
		    {
		        $aParams["source_lang"] = trim($source_lang);
		    }
		    if (trim($split_sentences) != "")
		    {
		        $aParams["split_sentences"] = trim($split_sentences);
		    }
		    if (trim($preserve_formatting) != "")
		    {
		        $aParams["preserve_formatting"] = trim($preserve_formatting);
		    }
		    if (trim($formality) != "")
		    {
		        $aParams["formality"] = trim($formality);
		    }
		    if (trim($glossary_id) != "")
		    {
		        $aParams["glossary_id"] = trim($glossary_id);
		    }
		    if (trim($tag_handling) != "")
		    {
		        $aParams["tag_handling"] = trim($tag_handling);
		    }
		    if (trim($non_splitting_tags) != "")
		    {
		        $aParams["non_splitting_tags"] = trim($non_splitting_tags);
		    }
		    if (trim($outline_detection) != "")
		    {
		        $aParams["outline_detection"] = trim($outline_detection);
		    }
		    if (trim($splitting_tags) != "")
		    {
		        $aParams["splitting_tags"] = trim($splitting_tags);
		    }
		    if (trim($ignore_tags) != "")
		    {
		        $aParams["ignore_tags"] = trim($ignore_tags);
		    }
		
			$aResponse = $this->__curl_post(
			    "translate",
			    $aParams
			);
			
            $response = @json_decode($aResponse["response"], TRUE);
            if (json_last_error() != JSON_ERROR_NONE)
                $aResponse["response"] = [];
			else
                $aResponse["response"] = $response;
            
            return $aResponse;
		}
		
		
		///
		/// Translating Documents Functions
		////////////////////////////////////////////////////////////////
		
		/**
		 * Uploading document
		 *
		 * The document function allows you to translate whole documents.
		 * 
		 * Once the document is fully uploaded, the translation starts immediately.
		 * 
		 * The maximum upload limit for any document is 10MB and 1.000.000 characters.
		 *
		 * Implements POST /document
		 *
		 * @return array
		 */
		public function uploadDocument(
		    string $source_lang,
		    string $target_lang,
		    string $formality,
		    string $glossary_id,
		    string $document_filepath
		){
		    $cfile = curl_file_create($document_filepath);
			$aResponse = $this->__curl_post_file(
			    "document",
			    [
			        "source_lang" => $source_lang,
			        "target_lang" => $target_lang,
			        "formality" => $formality,
			        "glossary_id" => $glossary_id,
			        //"filename" => basename($glossary_filepath),
			        //"file" => file_get_contents($glossary_filepath),
			        "file" => $cfile,
			    ]
			);
			
            $response = @json_decode($aResponse["response"], TRUE);
            if (json_last_error() != JSON_ERROR_NONE)
                $aResponse["response"] = [];
			else
                $aResponse["response"] = $response;
            
            return $aResponse;
		}
		
		/**
		 * Checking translation status
		 *
		 * The status of the document translation process can be checked by sending a status request to the document specific status.
		 *
		 * Implements POST /document/[document_id]
		 *
		 * @return array
		 */
		public function checkDocumentTranslationStatus(string $document_id, string $document_key)
		{
			$aResponse = $this->__curl_post("document/$document_id", ["document_key" => $document_key]);
			
            $response = @json_decode($aResponse["response"], TRUE);
            if (json_last_error() != JSON_ERROR_NONE)
                $aResponse["response"] = [];
			else
                $aResponse["response"] = $response;
            
            return $aResponse;
		}
		
		/**
		 * Downloading translated documents
		 *
		 * Once the status of the document translation process is "done", the result can be downloaded from the document specific download URL.
		 *
		 * Implements POST /document/[document_id]/result
		 *
		 * @return array
		 */
		public function downloadDocument(string $document_id, string $document_key)
		{
			$aResponse = $this->__curl_post("document/$document_id", ["document_key" => $document_key]);
			
            return $aResponse;
		}
		
		
		///
		/// Private Utils Functions
		////////////////////////////////////////////////////////////////
		
		/**
		 * Send a DELETE HTTP request to DeepL
		 *
		 * @param string $url - the url (without the base api part)
		 * @param array $hearders - Array of additional headers
		 *
		 * @return array
		 */
		private function __curl_delete($url, $hearders = NULL)
		{
	        $ch = curl_init();
	        
	        curl_setopt($ch, CURLOPT_URL, $this->url_api . $url);
	        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	        
	        $hearders = array_merge(["Authorization: DeepL-Auth-Key " . $this->auth_key], $hearders ?? []);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $hearders);
	        
	        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
	        
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	        curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
	        
	        // Disables certificate verification and proxy usage
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	        curl_setopt($ch, CURLOPT_PROXY, NULL);
	        
	        // Connection timeout in seconds
	        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	        
	        // Execution timeout in seconds
	        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	        
	        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
	        
	        curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
	        
	        $response = curl_exec($ch);
	        if ($response === FALSE)
	        {
	            $response = "";
	        }
	        
	        $errno_http = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
	        $errmsg_http = $this->__http_strerror($errno_http);
	        
	        $errno_curl = curl_errno($ch);
	        $errmsg_curl = curl_strerror($errno_curl);
	
		    // Since PHP 8.0 the curl_close() function no longer has an effect
		    if (version_compare(PHP_VERSION, '8.0.0', '<'))
		    {
		        curl_close($ch);
		    }
	        
	        return [
	            "errno_curl" => $errno_curl,
	            "errmsg_curl" => $errmsg_curl,
	            "errno_http" => $errno_http,
	            "errmsg_http" => $errmsg_http,
	            "response" => $response,
	        ];
		}
		
		/**
		 * Send a GET HTTP request to DeepL
		 *
		 * @param string $url - the url (without the base api part)
		 * @param array $hearders - Array of additional headers
		 *
		 * @return array
		 */
		private function __curl_get($url, $hearders = NULL)
		{
	        $ch = curl_init();
	        
	        curl_setopt($ch, CURLOPT_URL, $this->url_api . $url);
	        
	        $hearders = array_merge(["Authorization: DeepL-Auth-Key " . $this->auth_key], $hearders ?? []);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $hearders);
	        
	        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
	        
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	        curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
	        
	        // Disables certificate verification and proxy usage
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	        curl_setopt($ch, CURLOPT_PROXY, NULL);
	        
	        // Connection timeout in seconds
	        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	        
	        // Execution timeout in seconds
	        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	        
	        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
	        
	        curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
	        
	        $response = curl_exec($ch);
	        if ($response === FALSE)
	        {
	            $response = "";
	        }
	        
	        $errno_http = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
	        $errmsg_http = $this->__http_strerror($errno_http);
	        
	        $errno_curl = curl_errno($ch);
	        $errmsg_curl = curl_strerror($errno_curl);
	
		    // Since PHP 8.0 the curl_close() function no longer has an effect
		    if (version_compare(PHP_VERSION, '8.0.0', '<'))
		    {
		        curl_close($ch);
		    }
	        
	        return [
	            "errno_curl" => $errno_curl,
	            "errmsg_curl" => $errmsg_curl,
	            "errno_http" => $errno_http,
	            "errmsg_http" => $errmsg_http,
	            "response" => $response,
	        ];
		}
		
		/**
		 * Send a POST HTTP request to DeepL
		 *
		 * @param string $url - the url (without the base api part)
		 * @param array $hearders - Array of additional headers
		 *
		 * @return array
		 */
		private function __curl_post($url, $data = NULL, $hearders = NULL)
		{
	        $ch = curl_init();
	        
	        curl_setopt($ch, CURLOPT_URL, $this->url_api . $url);
	        curl_setopt($ch, CURLOPT_POST, TRUE);
	        
	        $hearders = array_merge(["Authorization: DeepL-Auth-Key " . $this->auth_key], $hearders ?? []);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $hearders);
	        
	        if (!is_null($data))
	        {
	            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->__url_encode_post_data($data));
	        }
	        
	        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
	        
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	        curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
	        
	        // Disables certificate verification and proxy usage
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	        curl_setopt($ch, CURLOPT_PROXY, NULL);
	        
	        // Connection timeout in seconds
	        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	        
	        // Execution timeout in seconds
	        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	        
	        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
	        
	        curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
	        
	        $response = curl_exec($ch);
	        if ($response === FALSE)
	        {
	            $response = "";
	        }
	        
	        $errno_http = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
	        $errmsg_http = $this->__http_strerror($errno_http);
	        
	        $errno_curl = curl_errno($ch);
	        $errmsg_curl = curl_strerror($errno_curl);
	
		    // Since PHP 8.0 the curl_close() function no longer has an effect
		    if (version_compare(PHP_VERSION, '8.0.0', '<'))
		    {
		        curl_close($ch);
		    }
	        
	        return [
	            "errno_curl" => $errno_curl,
	            "errmsg_curl" => $errmsg_curl,
	            "errno_http" => $errno_http,
	            "errmsg_http" => $errmsg_http,
	            "response" => $response,
	        ];
		}
		
		/**
		 * Send a POST HTTP request with a file to DeepL
		 *
		 * @param string $url - the url (without the base api part)
		 * @param array $data - Array of POST data
		 * @param array $hearders - Array of additional headers
		 *
		 * @return array
		 */
		private function __curl_post_file($url, $data = NULL, $hearders = NULL)
		{
	        $ch = curl_init();
	        
	        curl_setopt($ch, CURLOPT_URL, $this->url_api . $url);
	        curl_setopt($ch, CURLOPT_POST, TRUE);
	        
	        $hearders = array_merge(["Authorization: DeepL-Auth-Key " . $this->auth_key], $hearders ?? []);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $hearders);
	        
	        if (!is_null($data))
	        {
	            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	        }
	        
	        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
	        
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	        curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
	        
	        // Disables certificate verification and proxy usage
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	        curl_setopt($ch, CURLOPT_PROXY, NULL);
	        
	        // Connection timeout in seconds
	        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	        
	        // Execution timeout in seconds
	        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	        
	        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
	        
	        curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
	        
	        $response = curl_exec($ch);
	        if ($response === FALSE)
	        {
	            $response = "";
	        }
	        
	        $errno_http = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
	        $errmsg_http = $this->__http_strerror($errno_http);
	        
	        $errno_curl = curl_errno($ch);
	        $errmsg_curl = curl_strerror($errno_curl);
	
		    // Since PHP 8.0 the curl_close() function no longer has an effect
		    if (version_compare(PHP_VERSION, '8.0.0', '<'))
		    {
		        curl_close($ch);
		    }
	        
	        return [
	            "errno_curl" => $errno_curl,
	            "errmsg_curl" => $errmsg_curl,
	            "errno_http" => $errno_http,
	            "errmsg_http" => $errmsg_http,
	            "response" => $response,
	        ];
		}
		
	    /**
	     * Return the english name associated with an HTTP error code
	     *
	     * @param int|string $errno - HTTP error code
	     *
	     * @return string
	     */
		private function __http_strerror($errno)
		{
	        if (is_string($errno) && is_numeric)
	        {
	            if (is_numeric($errno))
	            {
	                $errno = intval($errno);
	            }
	            else
	            {
	                $errno = 0;
	            }
	        }
	        
            $http_status_codes = [
                // 1xx informational response
                100 => "Continue",
                101 => "Switching Protocols",
                102 => "Processing",
                103 => "Early Hints",
                
                // 2xx success
                200 => "OK",
                201 => "Created",
                202 => "Accepted",
                203 => "Non-Authoritative Information",
                204 => "No Content",
                205 => "Reset Content",
                206 => "Partial Content",
                207 => "Multi-Status",
                208 => "Already Reported",
                226 => "IM Used",
                
                // 3xx redirection
                300 => "Multiple Choices",
                301 => "Moved Permanently",
                302 => "Found",
                303 => "See Other",
                304 => "Not Modified",
                305 => "Use Proxy",
                306 => "Switch Proxy",
                307 => "Temporary Redirect",
                308 => "Permanent Redirect",
                
                // 4xx client errors
                400 => "Bad Request",
                401 => "Unauthorized",
                402 => "Payment Required",
                403 => "Forbidden",
                404 => "Not Found",
                405 => "Method Not Allowed",
                406 => "Not Acceptable",
                407 => "Proxy Authentication Required",
                408 => "Request Timeout",
                409 => "Conflict",
                410 => "Gone",
                411 => "Length Required",
                412 => "Precondition Failed",
                413 => "Payload Too Large",
                414 => "URI Too Long",
                415 => "Unsupported Media Type",
                416 => "Range Not Satisfiable",
                417 => "Expectation Failed",
                418 => "I'm a teapot",
                421 => "Misdirected Request",
                422 => "Unprocessable Entity",
                423 => "Locked",
                424 => "Failed Dependency",
                425 => "Too Early",
                426 => "Upgrade Required",
                428 => "Precondition Required",
                429 => "Too Many Requests",
                431 => "Request Header Fields Too Large",
                451 => "Unavailable For Legal Reasons",
                
                // 5xx server errors
                500 => "Internal Server Error",
                501 => "Not Implemented",
                502 => "Bad Gateway",
                503 => "Service Unavailable",
                504 => "Gateway Timeout",
                505 => "HTTP Version Not Supported",
                506 => "Variant Also Negotiates",
                507 => "Insufficient Storage",
                508 => "Loop Detected",
                510 => "Not Extended",
                511 => "Network Authentication Required",
                
                // DeepL specific code
                456 => "Quota exceeded",
                529 => "Too many requests",
            ];
            
            return $http_status_codes[$errno] ?? "";
		}
		
	    /**
	     * Return an array of data to POST encoded as an URL
	     *
	     * @param array $params - Data array
	     *
	     * @return string
	     */
        private function __url_encode_post_data(?array $params): string
        {
            $params = $params ?? [];
            $fields = [];
            foreach ($params as $key => $value) {
                $name = \urlencode($key);
                if (is_array($value)) {
                    $fields[] = implode(
                        '&',
                        array_map(
                            function (string $textElement) use ($name): string {
                                return $name . '=' . \urlencode($textElement);
                            },
                            $value
                        )
                    );
                } elseif (is_null($value)) {
                    // Parameters with null value are skipped
                } else {
                    $fields[] = $name . '=' . \urlencode($value);
                }
            }

            return implode("&", $fields);
        }
	}
}
