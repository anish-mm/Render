<?php

namespace Render;

class RenderArray {
	  
  /*
  * calls appropriate renderer to render $arr
  */
  public function handle(array $arr) {
    if (!isset($arr['type'])) {
      return "Type not specified for Render Array";
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
        $output = NULL;    
    }
    
    return $output;
  }
  
  /*
  * returns corresponding html for render arrays with type 'table'
  */
  private function tableRenderer(array $table) {
    /*
    * render array for table theme should contain
    * a 'type' = 'table',
    * 'headers' : string array, contains the headers for the table
    * 'rows' : array rows, contains arrays of strings/render arrays representing rows
    */
   
    $headers = isset($table['headers']) ? $table['headers'] : NULL;
    
    $rows = isset($table['rows']) ? $table['rows'] : NULL;
    
    //number of columns in the table
    $col_count = count($headers);
    
    //output html for table 
    $output = "<table>\n";
    
    //assign the headers
    $output .= "  <tr>\n";
    foreach ($headers as $value) {
      $output .= "    <th>" . $value . "</th>\n";
    }
    $output .= "  </tr>\n";
    
    //add html for the rows
    if ($rows != NULL) {
      foreach ($rows as $row) {
        $output .= "  <tr>\n";
        foreach ($row as $key=> $val) {
          //if more data in row than col_count, discard trailing values
          if ($key < $col_count) {
          	$output .= "    <td>"; 
          	if (is_array($val) && isset($val['type']) && $val['type'] != 'page') {
          	  $output .= $this->handle($val);
          	}
          	else {
          	  $output .= $val;
          	}
          	$output .= "</td>\n";
          }
          else {
            break;        
          }
        }
        $output .= "  </tr>\n";
      }
    }
  
    //conclude html for table
    $output .= "</table>\n";
    
    return $output;
  }
  
  /*
  * returns corresponding html for render arrays with type 'link'
  */
  private function linkRenderer($link) {
    
     /*
    * render array for link will have
    * a 'type'= 'link',
    * 'url'= url for the link,
    * 'text' = link text
    */
    
    extract($link, EXTR_SKIP);
     
    //output html for link
    $output = "<a href=";
    
    if (!isset($url)) {
      return("Url Not Set");
    }
  
    //add the url  
    $output .= "\"$url\">\n";
    
    //add the link text
    $output .= "  $text\n";
    
    //close the <a> tag
    $output .= "</a>\n";
    
    return $output;    
  }
  
  private function pageRenderer($arr) {
    /*
    * render array for page will have
    * 'type' as 'page',
    * 'title' = a string, heading of page,
    * 'body'= a string or array of strings, render arrays for links, tables.
    * 'tags' = array of strings.
    */  
  
    extract($arr, EXTR_SKIP);  
      
    $output = "<html>\n";
    
    //adding css to give border to tables. done for all tables
    $output .= "<style>\n table, th, td {border: 1px solid black; border-collapse: collapse;}</style>\n";
    
    $output .= "<body>\n";   
    
    //add title
    $output .= isset($title) ? "  <h1>" . $title . "</h1>\n" : "";
    
    //add body
    if(is_array($body)) {
      foreach ($body as $val) {
        if (is_string($val)) {
          $output .= "<p>" . $val . "</p>\n";
        }
        elseif (is_array($val) && isset($val['type']) && $val['type'] !== 'page') {
         $output .= $this->handle($val);  //error not checked...      
        } 
        else {
          $output = "No renderer available for this array";
          break;        
        }       
      }
    }
    elseif (is_string($body)) {
      $output .= "<p>" . $body . "</p>\n";
    }
    else {
      $output = "No renderer available for this array";    
    }
    
    //add tags 
    //for now added as ul of text.
    if (isset($tags) && is_array($tags)) {
      $output .= "<ul>\n";
      foreach ($tags as $val) {
        $output .= "  <li>" . $val . "</li>\n";      
      }   
      $output .= "</ul>\n";
    }
    
    //conclude body and html tags
    $output .= "</body>\n</html>\n";
    
    return $output;
  }
  
}
?>