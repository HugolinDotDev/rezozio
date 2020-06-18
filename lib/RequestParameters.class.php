<?php
/**
* RequestParameters
* @see RequestParameters\define
* @author Bruno.Bogaert[at]univ-lille1.fr
* @copyright Bruno.Bogaert[at]univ-lille1.fr
* @license https://creativecommons.org/licenses/by-nc-nd/2.0/legalcode  Creative Commons (CC BY-NC-ND 2.0)
 *
 *
 */

/**
 * Outil de test et de filtrage d'parameters reçus via HTTP (en GET ou en POST).
 * 
 * <h3>Download</h3>
 * <a href="http://www.fil.univ-lille1.fr/~bogaert/tw2/documents/RequestParameters/RequestParameters.zip">Download here</a>
*
* <h3>Exemples</h3>
* <h4>Exemple 1</h4>
* Pour prendre en compte les 3 paramètres :
* <ul>
* <li>numbers : tableau d'entiers >=10</li>
* <li>operation : 'sum' (par défaut) ou 'product'</li>
* <li>message : chaîne quelconque, optionnelle (chaîne vide par défaut)</li>
* </ul>
* on crée une instance de RequestParameters, à laquelle on applique les 3 définitions:
 * <pre>
 *  $args = new RequestParameters();
 *  $args->defineInt('numbers', ['dimension'=>'array','min_range'=>10]);
 *  $args->defineEnum('operation', ['sum','product'], ['default'=>'sum']);
 *  $args->defineString('message');
 * </pre>
 * <p> On constate que pour définir un paramètre on utilise une méthode <code>defineXXXX(nom)</code>. Il existe des méthodes pour les principaux
 * «types» de paramètres : <code>defineInt(...)</code>, <code>defineEnum(..)</code>, <code>defineNonEmptyString(..)</code> etc...<br/>
 * Des options de définition peuvent être ajoutées (dernier paramètre de la méthode), par exemple pour fixer une valeur par défaut ou pour indiquer que la paramètre est un tableau
 * </p>
 * <p>
 * La méthode <code>isValid()</code>  indique si tous paramètres obligatoires ont reçu une valeur correcte.<br/>
 * <code>isValid(nomParametre)</code> indique seulement la validité du paramètre mentionné. Par exemple <code>$args->isValid('operation')</code> <br/>
 * on peut aussi passer une liste de noms de paramètres, par exemple  <code>$args->isValid(['operation','numbers'])</code> <br/>
 * </p>
 * <p> En cas de validité, la valeur retenue (c'est à dire la valeur reçue ou la valeur par défaut en cas d'absence) peut être consultée
 * dans un attribut de même nom que le paramètre, par exemple <code>$args->operation</code> <br />
 * ou par la méthode  <code>getValue(nom)</code> : <code>$args->getValue('operation')</code>
 * Exemple :
 * <pre>
 *  $args = new RequestParameters();
 *  $args->defineInt('numbers', ['dimension'=>'array','min_range'=>10]);
 *  $args->defineEnum('operation', ['sum','product'], ['default'=>'sum']);
 *  $args->defineString('message');
 *  if ($args->isValid()) { // tous les paramètres sont corrects
 *     if ($args->operation == 'sum')
 *         $res = array_sum($args->numbers);
 *     else // sure it's 'product'
 *         $res = array_product($args->numbers);
 *	   echo $args->message . $res;
 *  }
 * else
 *   echo "Paramètres incorrects. " . implode(', ', $args->getErrorMessages());
 * </pre>
 
 * </pre>
 *
	* <h4>Exemple 2</h4>
	* Une méthode générique permet d'utiliser les filtres PHP prédéfinis. Par exemple pour filtrer une adresse IP :
  *
 * <pre>
 *  $args = new RequestParameters();
 *  $args->defineWithGenericFilter('ip1', FILTER_VALIDATE_IP);
 *  ...
   * </pre>
   *
   * 
	 * <h3>Documentation</h3>
	 * <h4>Get ou Post ?</h4>
	 * <strong>Par défaut, la classe autodétecte la méthode de passage des paramètres.</strong> ce qui conviendra à l'immense majorité des
	 * besoins. En cas de nécessité (seulement), la méthode peut être précisée à l'instanciation :
	 * <pre>
	 *  $args = new RequestParameters("post");
	 * </pre>
	 * <h4>Les méthodes <strong>defineXXXX()</strong></h4>
	 * <ul>
	 *	<li> le premier argument est toujours le nom du paramètre à prendre en compte</li>
	 *	<li> le dernier argument (facultatif) permet de passer un dictionnaire d'options (table associative)</li>
	 *</ul>
	 * Les méthodes defineXXXX fournies couvrent la majorité des besoins.
	 *
	 * <h4>Options</h4>
	 * <table border="1">
	 * <tr><th colspan="2">Communes à toutes les méthodes</th></tr>
	 *  <tr><td><tt>default</tt></td><td>définit une valeur par défaut qui s'applique si le paramètre est absent (mais pas si il est erroné).
	 *    La valeur fournie doit respecter les contraintes définies pour le paramètre.</td></tr>
	 *  <tr><td><tt>dimension</tt></td><td><b>scalar</b> (défaut) ou <b>array</b></td></tr>
	 *  <tr><td><tt>case</tt></td><td><b>to_lower</b>, <b>to_upper</b>, <b>as_is</b> (défaut).
	 *       La valeur reçue est mise en minuscules ou majuscules ou laissée inchangée.
	 *        La transformation éventuelle a lieu <strong>avant</strong> le test de validité</td></tr>
	 * <tr><th colspan="2">méthode <var>defineInt()</var></th></tr>
	 * <tr><td><tt>min_range</tt></td><td>valeur minimale</td></tr>
	 * <tr><td><tt>max_range</tt></td><td>valeur maximale</td></tr>
	 * <tr><th colspan="2">méthode <var>defineFloat()</var></th></tr>
	 * <tr><td><tt>decimal</tt></td><td>caractère séparateur de la partie décimale : '.' ou ',' </td></tr>
   * </table>
	 * <h4>Écrire une nouvelle méthode defineXXX()</h4>
	 * Pour définir de nouvelles méthodes «defineXXX()» on peut étendre la classe RequestParameters.
	 * Elles doivent faire appel à la méthode <var>defineWithGenericFilter(....)</var>, seule façon d'enregistrer un nouveau paramètre.
  *
	* @see RequestParameters\define
  * @author Bruno.Bogaert[at]univ-lille1.fr
  * @copyright Bruno.Bogaert[at]univ-lille1.fr
  * @license https://creativecommons.org/licenses/by-nc-nd/2.0/legalcode  Creative Commons (CC BY-NC-ND 2.0)
*/
class RequestParameters {
	/**
	 * @internal
	 */
	private $inputMethod;  			// INPUT_GET, INPUT_POST, ...

	/**
	 * @internal
	 */
	private $errorMessages = [];
	/**
	 * @internal
	 */
  private $arguments = [];   // assoc. array : key : argument name, value : $status
	                           // status is assoc array with keys 'value', 'error', 'rawValue'

	/**
	 * @internal
	 */
	private function addArgument($name, $status){
		if (isset($this->arguments[$name]))
			throw new Exception("argument $name allready defined");
		$this->arguments[$name] = $status;
		if ($status['error']){
			$this->errorMessages[] = "$name : {$status['error']}";
		}
	}
	/**
	 * @internal
	 */
	private function setArgumentValue($name, $value, $rawValue){
 	  if (is_null($value))
 	 		throw new Exception('value can\'t be null');
 	  $this->addArgument($name,['value'=> $value, 'error'=> NULL, 'rawValue'=> $rawValue]);
  }
	/**
	 * @internal
	 */
	private function rejectArgumentValue($name, $rawValue){
		$this->addArgument($name,['value'=>NULL, 'error'=>'rejected', 'rawValue'=> $rawValue]);
	}
	/**
	 * @internal
	 */
	private function missingArgument($name){
		$this->addArgument($name,['value'=>NULL, 'error'=>'missing', 'rawValue'=> NULL]);
	}
	
	/**
   *	Validity of parameters (global or some parameters only)
 	* @param null|string|string[] $scope   scope
 	* @return bool :<br />
 	*  global validity  if $scope is NULL or missing <br/>
 	*  validity of some parameters if $scope is a parameter name or an array of names : 	*	 
 	*/
 	public function isValid($scope = NULL){
 		if (is_null($scope)) // global validity
 			return count($this->errorMessages)==0;
 		if (! is_array($scope))
 			$scope = [$scope];   // scope : list of names
 		foreach ($scope as $name){
 			if ( !isset($this->arguments[$name]) || $this->arguments[$name]['error'] )
 				return FALSE;
 		}
 		return TRUE;
 	}
	/** 	
  *	Get argument's elected value.
 	*	@param string $name argument name
 	*	@return mixed argument value. returns null for missing or rejected raw value
 	* @throws Exception when $name is not a defined argument
  	*/
  public final function getValue($name){
 	 if (! isset($this->arguments[$name]))
	 		throw new Exception('Unknown argument');
	 else if ($this->arguments[$name]['error'])
	    return null;
	 else
 		  return $this->arguments[$name]['value'];
  }

  /**
  	*	Parameter's elected value.
 	* Allows access to argument values using pseudo property notation :<br/>
 	*  $paramSet->arg1  is an equivalent to $paramSet->getValue('arg1')
 	*	@param string $name argument name
 	*	@return mixed argument value. returns null for missing or rejected raw value
  	*/
  public final function __get($name){
 	 try {
 		 return html_entity_decode($this->getValue($name), ENT_QUOTES);
 	 } catch (Exception $e){
 			 if (isset($this->{$name})){
 				 $level = E_USER_ERROR;
 				 $message = "Cannot access private or protected property ";
 			 } else {
 				 $level = E_USER_NOTICE;
 				 $message = "Undefined property ";
 			 }
 			 $caller = debug_backtrace()[0];
 			 $message .= "<b>{$caller['class']}::\${$name}</b> in <b>{$caller['file']}</b> on line <b>{$caller['line']}</b><br />\n";
 			 trigger_error($message,$level);
 	 }
 }

 /**
 * Elected values
 * @param bool $fullMode
 * @return array parameters values. Only valid parameters are returned, unless $fullMode is true
 */
	public final function getValues($fullMode = false){
		$res =[];
		foreach ($this->arguments as $name => $status) {
			if ($fullMode || !$status['error'])
					 $res[$name]=html_entity_decode($status['value'], ENT_QUOTES);
		}
		return $res;
 }
 
 
 /**
 * Parameters status
 * @return dictionary of parameters :
 *   parameter name =>  ['value'=> elected value,  'error'=> parm error or NULL, 'rawValue'=> received value (unfiltered))
 */
	public final function getStatus(){
		return $this->arguments;
  }

 	/**
 	 *	Erroneous parameters 
 	 *	@return  array  dictionary  of erroneous parameters :
 	 *	  parameter name   =>   "rejected" or "missing"
 	 */
 	public final function getErrors(){
		$res =[];
		foreach ($this->arguments as $name => $status) {
			if ($status['error'])
					 $res[$name]=$status['error'];
		}
		return $res;
 	}

	/**
	 * Error messages list
	 * @return string[] 
	 */
	 public final function getErrorMessages(){
		 return $this->errorMessages;
	 }
	 
	 
/**
 * Raw values (unfiltered)
 * @return array  dictionary of non filtered values <br/>
 * <strong> Don't use them in PHP programs, except for somme error messages.</strong>
 */
 public final function getRawValues(){
		$res =[];
		foreach ($this->arguments as $name => $status) {
					 $res[$name]=$status['rawValue'];
		}
		return $res;
 }


 	/**
	 * @internal
	 */
private function prepareFilterOptions($filter, $options, $flags){
	 // add default options
	 $options = array_merge(['dimension'=>'scalar'],$options);

	 // ignore given dimension flags and replace by dimension option
	 if ($options['dimension'] =='array')
		 $result['flags'] = $flags & ~FILTER_REQUIRE_SCALAR & ~FILTER_FORCE_ARRAY | FILTER_REQUIRE_ARRAY;
	 else  // scalar
		 $result['flags'] = $flags & ~FILTER_REQUIRE_ARRAY & ~FILTER_FORCE_ARRAY | FILTER_REQUIRE_SCALAR;

	 // set filter options
	 if ($filter === FILTER_CALLBACK){  // for this filter, options must contain only callback
		 if (!isset($options['callback']))
			 throw new Exception("FILTER_CALLBACK needs a callback");
		 $result['options'] = $options['callback'];
	 }
	 else { // don't use buit-in default mechanism
		 $result['options'] = array_diff_key($options,['default'=>1]);
	 }
	 return $result;
 }

 	/**
	 * @internal
	 */
private function filterValue($v, $filter, $filterOptions){
	 $res = filter_var($v, $filter, $filterOptions);
	 if ($filterOptions['flags'] & FILTER_REQUIRE_ARRAY){
		 return (is_array($res) && !in_array(false, $res ,true)) ?	$res : false;
	 } else {
		 return (!is_array($res) && $res !== false) ?	$res : false;
	 }

 }

/**
 * Defines a new parameter, using PHP filter.
 * @param string $name Parameter name
 * @param int $filter Filter {@see http://php.net/manual/fr/filter.filters.php}
 * @param string[] $options Associative array : optionName=>optionValue.
 * @param int $flags
 *
 * @return mixed|null
 */
 public final function defineWithGenericFilter($name, $filter, $options=[], $flags=0){
	 $filterOptions = $this->prepareFilterOptions($filter, $options, $flags);
	 $default = @$options['default'];
	 if ($default !== NULL){ 	// verify default value	validity
		 $default = $this->filterValue($default, $filter, $filterOptions);
		 if ($default === FALSE)
			 throw new Exception("Incorrect default value : " . json_encode($options['default']));
	 }
	 $rawValue = filter_input($this->inputMethod, $name, FILTER_UNSAFE_RAW, $filterOptions);
	 $v = is_null($rawValue) ? $default : $rawValue; 	 // apply default value

	 $case=@$options['case'];
	 if ($case == 'to_upper')
	 		$v = mb_strtoupper($v);
	 else if ($case == 'to_lower')
	 		$v = mb_strtolower($v);

	 if (is_null($v)){
		 $this->missingArgument($name);
	 }
	 else {
		 $v = $this->filterValue($v, $filter, $filterOptions);
		 if ($v === FALSE)
		 	$this->rejectArgumentValue($name, $rawValue);
		 else
		 	$this->setArgumentValue($name, $v, $rawValue);
	 }
	 return $v;
 }



	/**
	 * Defines a new parameter. Parameter value can be any string and will be sanitized.
	 * for scalar parameter, default value is '', unless user specifies an other default value in $options['default'].
	 * @param string $name Parameter name
	 * @param string[] $options Options : 'default', 'dimension'.
	 * @return string|null Elected value (sanitized), if accepted; false if value is rejected; null if value is missing and there is no default value
	 */
	public final function defineString($name, $options=[]){  // defaults to ''
		if (! isset($options['dimension']) || $options['dimension']=='scalar')
				$options = array_merge(['default'=>''],$options);
		return $this->defineWithGenericFilter($name,FILTER_SANITIZE_STRING,$options);
	}

	/**
	 * Defines a new parameter. Parameter value can be any non empty string and will be sanitized.
	 * @param string $name Parameter name
	 * @param string[] $options Options : 'default', 'dimension'.
	 * @return string|null Elected value (sanitized) if accepted; false if value is rejected; null if parameter is missing and there is no default value
	 */
	public final function defineNonEmptyString($name, $options=[]){
		$options['callback']=
					function($v) {$v=filter_var($v,FILTER_SANITIZE_STRING); return ($v!='')?$v:false;} ;
		return $this->defineWithGenericFilter($name, FILTER_CALLBACK, $options);
	}
	/**
	 * Defines a new parameter. Accepted values are listed in <var>$values</var>
	 * @param string $name Parameter name
	 * @param string[] $values Allowed values
	 * @param string[] $options Options : 'default', 'dimension'.
	 * @return string|null Elected value if accepted; false if value is rejected; null if parameter is missing and there is no default value
	 */
	public final function defineEnum($name, array $values, $options=[]){
		$options['callback']=
							function($v) use ($values){return in_array($v,$values)?$v:false;} ;
		return $this->defineWithGenericFilter($name, FILTER_CALLBACK, $options);
	}

	/**
	 * Define a new parameter. Accepted values are specified by a regular expression.
	 * @param string $name Parameter name
	 * @param string $regExp Regular expression
	 * @param string[] $options Options : 'default', 'dimension'.
	 * @return string|null Elected value if accepted; false if value is rejected; null if parameter is missing and there is no default value
	 */
	public final function defineRegExp($name, $regExp, $options=[]){
		$options['regexp']= $regExp;
		return $this->defineWithGenericFilter($name, FILTER_VALIDATE_REGEXP, $options);
	}

	/**
	 * Define a new parameter. Parameter value must be an integer
	 * @param string $name Parameter name
	 * @param string[] $options Options : 'default', 'dimension', 'min_range' (min value), 'max_range' (max value)
	 * @return integer|null Elected value if accepted; false if value is rejected; null if parameter is missing and there is no default value
	 */
	public final function defineInt($name, $options=[]){
		return $this->defineWithGenericFilter( $name, FILTER_VALIDATE_INT, $options );
	}

	/**
	 * Define a new parameter. Parameter value must be a number
	 * @param string $name Parameter name
	 * @param string[] $options Options : 'default', 'dimension', 'decimal' (decimal separator)
	 * @return float|null Elected value if accepted; false if value is rejected; null if parameter is missing and there is no default value
	 */
	public final function defineFloat($name, $options=[]){
		return $this->defineWithGenericFilter( $name, FILTER_VALIDATE_FLOAT, $options );
	}


	/**
	* Constructor
	*	@param string|int $inputMethod : method used, given as string "get", "post", "auto" or as constant : INPUT_GET, INPUT_POST
	*/
	public final function __construct($inputMethod = "auto"){
		if (is_string($inputMethod)){ // translate string to constant value
			$method = strtoupper($inputMethod);
			if ($method=="AUTO"){ // detect method from server infos
				$method = $_SERVER['REQUEST_METHOD'];
			}
			$method = "INPUT_".$method;
			if (!defined($method)) // constant undefined
			   throw new Exception("unknown input method : $method");
			$inputMethod = constant($method); // constant value
		}
  	$this->inputMethod = $inputMethod;

	
	}


}
?>
