<?php

namespace Render;

use Exception;

/**
 * class that renders arrays into corresponding html
 */
class RenderArray
{
    /**
     * renders $arr to create corresponding html
     * 
     * @param $arr array the render array to be processed
     * 
     * @return string either corresponding html or an error message
     */
    public function preHandle(array $arr)
    {
        try {
            return $this->handle($arr);
        } catch (Exception $e) {
            return 'Caught exception: '.$e->getMessage()."\n";
        }
    }
    
    /**
     * renders $arr to create the corresponding html
     * 
     * @param $arr array the render array to be processed
     * 
     * @return string the html for $arr
     */
    private function handle(array $arr)
    {
        if (!isset($arr['type'])) {
            throw new Exception("Type not specified for Render Array");
        }
    
        switch ($arr['type']) {
            case 'table':
                $output = $this->tableRenderer($arr);
                break;
            
            case 'link':
                $output = $this->linkRenderer($arr);
                break;
            
            case 'page':
                $output = $this->pageRenderer($arr);
                break;
            
            default:
                throw new exception("No renderer available for this array");
        }
        
        return $output;
    }
    
    /**
     * renders table render array to corresponding html
     * 
     * @param $table array render array for a table
     * 
     * @return string html for $table
     */
    private function tableRenderer(array $table)
    {
        if (!isset($table['headers']) || !is_array($table['headers'])) {
            throw new Exception("Table headers not set.");
        }
        
        $headers = $table['headers'];
        $rows = (isset($table['rows']) && is_array($table['rows'])) ?
             $table['rows'] : [];
        
        //number of columns in the table
        $col_count = count($headers);
        
        //output html for table 
        $output = "<table>\n";
        
        //assign the headers
        $output .= "  <tr>\n";
        
        foreach ($headers as $value) {
            $output .= "    <th>".$value."</th>\n";
        }
        
        $output .= "  </tr>\n";
        
        //add html for the rows
        foreach ($rows as $row) {
            if (!is_array($row)) {
                throw new Exception("Row not proper in table Render Array");
            }
            
            $output .= "  <tr>\n";
            
            foreach ($row as $key => $val) {
                //if more data in row than col_count, discard trailing values
                if ($key < $col_count) {
                    $output .= "    <td>";
                    
                    if (is_array($val) && isset($val['type']) &&
                         'page' != $val['type']) {
                        $output .= $this->handle($val);
                    } elseif (is_string($val)) {
                        $output .= $val;
                    } else {
                        throw new Exception("Row not proper in
                             table Render Array");
                    }
                    
                    $output .= "</td>\n";
                } else {
                    break;
                }
            }
            
            $output .= "  </tr>\n";
        }    
        
        //conclude html for table
        $output .= "</table>\n";
        
        return $output;
    }
    
    /*
     * renders link render array to corresponding html
     * 
     * @param $link array render array for a link
     * 
     * @return string html for $link
     */
    private function linkRenderer(array $link)
    {
        extract($link, EXTR_SKIP);
        
        if (!isset($url)) {
            throw new exception("Url not specified for link.");
        }
        
        //output html for link
        $output = "<a href=";
        
        //add the url
        $output .= "\"$url\">\n";
        
        //add the link text
        $output .= "  ".((isset($text) && is_string($text)) ?
             $text : "Click Here")."\n";
        
        //close the <a> tag
        $output .= "</a>\n";
        
        return $output;
    }
    
    /**
     * renders page render array to corresponding html
     * 
     * @param $arr array render array for a page
     * 
     * @return array html for $arr
     */
    private function pageRenderer(array $arr)
    {
        extract($arr, EXTR_SKIP);
        
        $output = "<html>\n";
        
        //adding css to give border to tables. done for all tables
        $output .= "<style>\n
             table, th, td {border: 1px solid black; border-collapse: collapse;}
             </style>\n";
        
        $output .= "<body>\n";
        
        //add title
        $output .= isset($title) ? "  <h1>".$title."</h1>\n" : "";
        
        //add body
        if (is_array($body)) {
            foreach ($body as $val) {
                if (is_string($val)) {
                    $output .= "<p>".$val."</p>\n";
                } elseif (is_array($val) && isset($val['type']) &&
                     'page' != $val['type']) {
                    $output .= $this->handle($val);
                } else {
                    throw new Exception("No renderer available
                         for this array / type");
                }
            }
        } elseif (is_string($body)) {
            $output .= "<p>".$body."</p>\n";
        } else {
            throw new Exception("No renderer available for this array / type");
        }
        
        //add tags 
        if (isset($tags) && is_array($tags)) {
            $output .= "<ul>\n";
            
            foreach ($tags as $val) {
                if (!is_array($val) || !isset($val['type']) ||
                     'link' != $val['type']) {
                    throw new Exception("No renderer available for
                         this array / type");
                }
                
                $output .= "  <li>" . $this->handle($val) . "</li>\n";
            } 
            
            $output .= "</ul>\n";
        } elseif (isset($tags)) {
            throw new Exception("No renderer available for this array / type");
        }
        
        //conclude body and html tags
        $output .= "</body>\n</html>\n";
        
        return $output;
    }
}
