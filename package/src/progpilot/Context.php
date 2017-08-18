<?php

/*
 * This file is part of ProgPilot, a static analyzer for security
 *
 * @copyright 2017 Eric Therond. All rights reserved
 * @license MIT See LICENSE at the root of the project for more info
 */


namespace progpilot;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

class Context {

	private $mycode;
	private $current_op;
	private $current_block;
	private $current_line;
	private $current_column;
	private $current_func;
	private $first_file;
	private $classes;
	private $functions;
	private $path;
	private $analyze_includes;
	private $analyze_js;
	private $configuration_file;

	public $inputs;
	public $outputs;

	public function __construct() 
	{
		$this->configuration_file = null;
		$this->analyze_includes = true;
		$this->analyze_js = true;
		$this->first_file = "";

		$this->reset_internal_values();

		$this->inputs = new \progpilot\Inputs\MyInputs;
		$this->outputs = new \progpilot\Outputs\MyOutputs;
	}

	public function reset_internal_values()
	{
		$this->current_op = null;
		$this->current_block = null;
		$this->current_line = -1;
		$this->current_column = -1;
		$this->current_func = null;
		$this->path = null;

		$this->classes = new \progpilot\Dataflow\Classes;
		$this->functions = new \progpilot\Dataflow\Functions;
		$this->mycode = new \progpilot\Code\MyCode;
	}

	public function get_analyze_js()
	{
		return $this->analyze_js;
	}

	public function get_analyze_includes()
	{
		return $this->analyze_includes;
	}

	public function set_analyze_js($analyze_js)
	{
		$this->analyze_js = $analyze_js;
	}

	public function set_analyze_includes($analyze_includes)
	{
		$this->analyze_includes = $analyze_includes;
	}

	public function get_mycode()
	{
		return $this->mycode;
	}

	public function get_current_op()
	{
		return $this->current_op;
	}

	public function get_current_block()
	{
		return $this->current_block;
	}

	public function get_current_line()
	{
		return $this->current_line;
	}

	public function get_current_column()
	{
		return $this->current_column;
	}

	public function get_current_func()
	{
		return $this->current_func;
	}

	public function get_classes()
	{
		return $this->classes;
	}

	public function get_functions()
	{
		return $this->functions;
	}

	public function get_inputs()
	{
		return $this->inputs;
	}

	public function get_outputs()
	{
		return $this->outputs;
	}

	public function get_path()
	{
		return $this->path;
	}

	public function get_first_file()
	{
		return $this->first_file;
	}

	public function set_first_file($first_file)
	{
		$this->first_file = $first_file;
	}

	public function set_path($path)
	{
		$this->path = $path;
	}


	public function set_mycode($mycode)
	{
		$this->mycode = $mycode;
	}

	public function set_current_op($current_op)
	{
		$this->current_op = $current_op;
	}

	public function set_current_block($current_block)
	{
		$this->current_block = $current_block;
	}

	public function set_current_line($current_line)
	{
		$this->current_line = $current_line;
	}

	public function set_current_column($current_column)
	{
		$this->current_column = $current_column;
	}

	public function set_current_func($current_func)
	{
		$this->current_func = $current_func;
	}

	public function set_classes($classes)
	{
		$this->classes = $classes;
	}

	public function set_functions($functions)
	{
		$this->functions = $functions;
	}

	public function set_inputs($inputs)
	{
		$this->inputs = $inputs;
	}
	
	
	public function set_configuration($file)
	{
        $this->configuration_file = $file;
	}

	public function get_configuration()
	{
		return $this->configuration_file;
	}

	public function read_configuration()
	{
        try 
        {
            $yaml = new Parser();
            $value = $yaml->parse(file_get_contents($this->configuration_file));
            
            if(is_array($value))
            {
                if(isset($value["inputs"]))
                {
                    if(isset($value["inputs"]["set_sources"]))
                        $this->inputs->set_sources($value["inputs"]["set_sources"]);
                        
                    if(isset($value["inputs"]["set_sinks"]))
                        $this->inputs->set_sinks($value["inputs"]["set_sinks"]);
                    
                    if(isset($value["inputs"]["set_validators"]))
                        $this->inputs->set_validators($value["inputs"]["set_validators"]);
                    
                    if(isset($value["inputs"]["set_sanitizers"]))
                        $this->inputs->set_sanitizers($value["inputs"]["set_sanitizers"]);
                        
                    if(isset($value["inputs"]["set_folder"]))
                        $this->inputs->set_folder($value["inputs"]["set_folder"]);
                        
                    if(isset($value["inputs"]["set_file"]))
                        $this->inputs->set_file($value["inputs"]["set_file"]);
                    
                    if(isset($value["inputs"]["set_code"]))
                        $this->inputs->set_code($value["inputs"]["set_code"]);
                    
                    if(isset($value["inputs"]["set_includes"]))
                        $this->inputs->set_includes($value["inputs"]["set_includes"]);
                    
                    if(isset($value["inputs"]["set_false_positives"]))
                        $this->inputs->set_false_positives($value["inputs"]["set_false_positives"]);
                }
                
                if(isset($value["outputs"]))
                {
                    if(isset($value["outputs"]["tainted_flow"]))
                        $this->outputs->tainted_flow($value["outputs"]["tainted_flow"]);
                        
                    if(isset($value["outputs"]["resolve_includes"]))
                        $this->outputs->resolve_includes($value["outputs"]["resolve_includes"]);
                    
                    if(isset($value["outputs"]["resolve_includes_file"]))
                        $this->outputs->resolve_includes_file($value["outputs"]["resolve_includes_file"]);
                }
                
                if(isset($value["options"]))
                {
                    if(isset($value["options"]["set_analyze_js"]))
                        $this->set_analyze_js($value["options"]["set_analyze_js"]);
                        
                    if(isset($value["options"]["set_analyze_includes"]))
                        $this->set_analyze_includes($value["options"]["set_analyze_includes"]);
                }
            }
        }
        catch (ParseException $e) 
        {
            throw new \Exception(Lang::UNABLE_TO_PARSER_YAML);
        }
	}
}

?>
