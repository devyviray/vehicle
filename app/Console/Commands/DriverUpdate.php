<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Vehicle;
use App\Driver;
use App\DriverTruck;
use App\Driverversions;
use App\Truck;
use App\BackgroundJobLogs;

class DriverUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:driver';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update drivers info from pickup portal drivers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $getBGJobs = BackgroundJobLogs::where('name','update:driver')->orderBy('id','desc')->first();

        if (is_null($getBGJobs)) {
            BackgroundJobLogs::create([
                'name' => 'update:driver',
                'start_time' => date('Y-m-d H:i:s'),
            ]);
            $getBGJobs = BackgroundJobLogs::where('name','update:driver')->orderBy('id','desc')->first();

            $dateFilter = !is_null($getBGJobs->end_time) ? $getBGJobs->end_time : $getBGJobs->start_time;
        } elseif (!is_null($getBGJobs->end_time)) {
            $getBGJobs = BackgroundJobLogs::where('name','update:driver')->orderBy('id','desc')->first();

            $dateFilter = !is_null($getBGJobs->end_time) ? $getBGJobs->end_time : $getBGJobs->start_time;

            BackgroundJobLogs::where('name','update:driver')->create([
                'name' => 'update:driver',
                'start_time' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $getBGJobs = BackgroundJobLogs::where('name','update:driver')->orderBy('id','desc')->first();

            $dateFilter = !is_null($getBGJobs->end_time) ? $getBGJobs->end_time : $getBGJobs->start_time;
        }

        $drivers = Driverversions::with('drivers_info')
            ->whereDate('updated_at', '>', $dateFilter)
            ->get();

        foreach ($drivers as $driver) {
            $vehicles = Vehicle::where('plate_number', '=', $driver->plate_number)->whereDate('validity_end_date','>=', date('Y-m-d'));
            $checkVehicle = $vehicles->first();

            if ($driver->drivers_info) {
                $driver_name = $driver->drivers_info->name;
                $final_driver_name = str_replace('.','',$driver_name);
                /* $driver_name1 = str_replace('  JR','',$driver_name);
                $driver_name1 = str_replace(' JR','',$driver_name);
                $driver_name2 = str_replace(' SR','',$driver_name1);
                $final_driver_name = str_replace(' III','',$driver_name3); */

                $explode_driver = explode(' ', $final_driver_name);

                if (count($explode_driver) == 2) {
                    $firstname = substr($explode_driver[0], 0, 1);
                    $lastname = $explode_driver[1];
                } else {
                    $firstname = substr($explode_driver[0], 0, 1);
                    $suffix = '';
                    $previousData = '';
                    $multipleLastName = '';
                    for ($i=0; $i < count($explode_driver); $i++) {
                        if ($explode_driver[$i] == '' || $explode_driver[$i] == ' ') {
                            if (str_contains($explode_driver[$i], 'JR') || str_contains($explode_driver[$i], 'SR') || str_contains($explode_driver[$i], 'III')) {
                                $suffix = ' ' . $explode_driver[$i];
                            }
                            break;
                        } else {
                            if (str_contains($explode_driver[$i], 'JR') || str_contains($explode_driver[$i], 'SR') || str_contains($explode_driver[$i], 'III')) {
                                $suffix = ' ' . $explode_driver[$i];
                            } elseif ($previousData != $explode_driver[$i]) {
                                $previousData = $explode_driver[$i];
                            }

                            if ($explode_driver[$i] == 'DE' || $explode_driver[$i] == 'DEL' || $explode_driver[$i] == 'DELOS' || $explode_driver[$i] == 'DE LOS' || $explode_driver[$i] == 'DELA' || $explode_driver[$i] == 'DELAS' || $explode_driver[$i] == 'DE LAS') {
                                $multipleLastName = $explode_driver[$i] . ' ';
                            }

                            $lastname = $multipleLastName . $previousData . $suffix;
                        }
                        
                    }
                }

                $name = $firstname . '. ' . $lastname;

                if ($checkVehicle) {
                    $vehicles->update([
                        'driver_name' => $name,
                        'driver_validity_start_date' => date('Y-m-d', strtotime($driver->drivers_info->start_validity_date)),
                        'driver_validity_end_date' => date('Y-m-d', strtotime($driver->drivers_info->end_validity_date)),
                    ]);
                }
            }
        }

        $getBGJobs->end_time = date('Y-m-d H:i:s');
        $getBGJobs->save();
    }

    protected $dict = array(
    );
  
    protected $not_nicknames = array( "(hons)");
  
    public static function parse($name) {
      $parser = new self();
      return $parser->parse_name($name);
    }
  
    public function parse_name($full_name) {
  
      # Remove leading/trailing whitespace
      $full_name = trim($full_name);
  
      // remove any words that don't add value
      // $full_name = str_replace("(Hons)", '', $full_name );
      // $full_name = str_replace("(hons)", '', $full_name );
  
      # Setup default vars
      extract(array('salutation' => '', 'fname' => '', 'initials' => '', 'lname' => '', 'lname_base' => '', 'lname_compound' => '', 'suffix' => ''));
  
      # Find all the professional suffixes possible
      $professional_suffix = $this->get_pro_suffix($full_name);
  
      // The position of the first professional suffix denotes the end of the name and the start of suffixes
      $first_suffix_index = mb_strlen($full_name);
      foreach ($professional_suffix as $key => $psx) {
        $start = mb_strpos($full_name, $psx);
        if( $start === FALSE ) {
          echo "ASSERT ERROR, the professional suffix:".$psx." cannot be found in the full name:".$full_name."<br>";
          continue;
        }
        if( $start < $first_suffix_index) {
          $first_suffix_index = $start;
        }
      }
  
      // everything to the right of the first professional suffix is part of the suffix
      $suffix = mb_substr($full_name, $first_suffix_index);
  
      // remove the suffixes from the full_name
      $full_name = mb_substr($full_name, 0, $first_suffix_index);
  
      # Deal with nickname, push to array
      $has_nick = $this->get_nickname($full_name);
      if ($has_nick) {
        # Remove wrapper chars from around nickname
        $name['nickname'] = mb_substr($has_nick, 1, (mb_strlen($has_nick) - 2));
        # Remove the nickname from the full name
        $full_name = str_replace($has_nick, '', $full_name);
        # Get rid of consecutive spaces left by the removal
        $full_name = str_replace('  ', ' ', $full_name);
      }
      
      # Grab a list of words from the remainder of the full name
      $unfiltered_name_parts = $this->break_words($full_name);
  
      # Is first word a title or multiple titles consecutively?
      if( count($unfiltered_name_parts)) {
        // only start looking if there are any words left in the name to process
        while (count($unfiltered_name_parts) > 0 && $s = $this->is_salutation($unfiltered_name_parts[0])) {
          $salutation .= "$s ";
          array_shift($unfiltered_name_parts);
        }
        $salutation = trim($salutation);
        // Find if there is a line suffix, if so then move it out
        # Is last word a suffix or multiple suffixes consecutively?
        while (count($unfiltered_name_parts) > 0 && $s = $this->is_line_suffix($unfiltered_name_parts[count($unfiltered_name_parts)-1], $full_name)) {
          if( $suffix != "") {
            $suffix = $s.", ".$suffix;
          } else {
            $suffix .= $s;
          }
          array_pop($unfiltered_name_parts);
        }
        $suffix = trim($suffix);
      } else {
        $salutation = "";
        $suffix = "";
      }
  
      // Re-pack the unfiltered name parts array and exclude empty words
      $name_arr = array();
      foreach ($unfiltered_name_parts as $key => $name_part) {
        $name_part = trim($name_part);
        $name_part = rtrim($name_part,',');
        if(mb_strlen($name_part) == '1') {
          // If any word left is of one character that is not alphabetic then it is not a real word, so remove it
          if( ! $this->mb_ctype_alpha($name_part)) {
            $name_part = "";
          }
        }
        if(mb_strlen(trim($name_part)) ) {
          $name_arr[] = $name_part;
        }
      }
      $unfiltered_name_parts = $name_arr;
  
      # set the ending range after prefix/suffix trim
      $end = count($unfiltered_name_parts);
  
      # concat the first name
      for ($i=0; $i<$end-1; $i++) {
        $word = $unfiltered_name_parts[$i];
        # move on to parsing the last name if we find an indicator of a compound last name (Von, Van, etc)
        # we use $i != 0 to allow for rare cases where an indicator is actually the first name (like "Von Fabella")
        if ($this->is_compound($word) && $i != 0) {
          break;
        }
        # is it a middle initial or part of their first name?
        # if we start off with an initial, we'll call it the first name
        if ($this->is_initial($word)) {
          # is the initial the first word?
          if ($i == 0) {
            # if so, do a look-ahead to see if they go by their middle name
            # for ex: "R. Jason Smith" => "Jason Smith" & "R." is stored as an initial
            # but "R. J. Smith" => "R. Smith" and "J." is stored as an initial
            if ($this->is_initial($unfiltered_name_parts[$i+1])) {
              $fname .= " ".mb_strtoupper($word);
            }
            else {
              $initials .= " ".mb_strtoupper($word);
            }
          }
          # otherwise, just go ahead and save the initial
          else {
            $initials .= " ".mb_strtoupper($word);
          }
        }
        else {
          $fname .= " ".$this->fix_case($word);
        }
      }
  
      if( count($unfiltered_name_parts)) {
        # check that we have more than 1 word in our string
        if ($end-0 > 1) {
          # concat the last name and split last name in base and compound
          for ($i; $i < $end; $i++) {
            if ($this->is_compound($unfiltered_name_parts[$i])) {
              $lname_compound .= " ".$unfiltered_name_parts[$i];
            } else {
              $lname_base .= " ".$this->fix_case($unfiltered_name_parts[$i]);
            }
            $lname .= " ".$this->fix_case($unfiltered_name_parts[$i]);
          }
        }
        else {
          # otherwise, single word strings are assumed to be first names
          $fname = $this->fix_case($unfiltered_name_parts[$i]);
        }
      } else {
        $fname = "";
      }
  
      # return the various parts in an array
      $name['salutation'] = $salutation;
      $name['fname'] = trim($fname);
      $name['initials'] = trim($initials);
      $name['lname'] = trim($lname);
      $name['lname_base'] = trim($lname_base);
      $name['lname_compound'] = trim($lname_compound);
      $name['suffix'] = $suffix;
      return $name;
    }
  
    public function break_words($name) {
    }
  
    public function get_pro_suffix($name) {
  
      $found_suffix_arr = array();
      foreach ($this->dict['suffixes']['prof'] as $suffix) {
        if (preg_match('/[,\s]+'.preg_quote($suffix).'\b/i', $name, $matches)) {
          $found_suffix = trim($matches[0]);
          $found_suffix = rtrim($found_suffix,',');
          $found_suffix = ltrim($found_suffix,',');
          $found_suffix_arr[] = trim($found_suffix);
        }
      }
      return $found_suffix_arr;
    }
  
    protected function get_nickname($name) {
      if (preg_match("/[\(|\"].*?[\)|\"]/", $name, $matches)) {
        if( ! in_array( mb_strtolower($matches[0]), $this->not_nicknames ) ) {
          return $matches[0];
        } else {
          return false;
        }
      }
      return false;
    }
  
    protected function is_line_suffix($word, $name) {
  
      # Ignore periods and righ commas, normalize case
      $word = str_replace('.', '', mb_strtolower($word));
      $word = rtrim($word,',');
  
      # Search the array for our word
      $line_match = array_search($word, array_map('mb_strtolower', $this->dict['suffixes']['line']));
  
      # Now test our edge cases based on lineage
      if ($line_match !== false) {
        # Store our match
        $matched_case = $this->dict['suffixes']['line'][$line_match];
  
        # Remove it from the array
        $temp_array = $this->dict['suffixes']['line'];
        unset($temp_array[$line_match]);
  
        # Make sure we're dealing with the suffix and not a surname
        if ($word == 'senior' || $word == 'junior') {
  
          # If name is Joshua Senior, it's pretty likely that Senior is the surname
          # However, if the name is Joshua Jones Senior, then it's likely a suffix
          if ($this->mb_str_word_count($name) < 3) {
            return false;
          }
  
          # If the word Junior or Senior is contained, but so is some other
          # lineage suffix, then the word is likely a surname and not a suffix
          foreach ($temp_array as $suffix) {
            if (preg_match("/\b".$suffix."\b/i", $name)) {
              return false;
            }
          }
        }
        return $matched_case;
      }
      return false;
    }
  
    protected function is_salutation($word) {
      $word = str_replace('.', '', mb_strtolower($word));
      foreach ($this->dict['prefix'] as $replace => $originals) {
        if (in_array($word, $originals)) {
          return $replace;
        }
      }
      return false;
    }
  
    protected function is_compound($word) {
      return in_array(mb_strtolower($word), $this->dict['compound']);
    }
  
    protected function is_initial($word) {
      return ((mb_strlen($word) == 1) || (mb_strlen($word) == 2 && $word[1] == "."));
    }
  
    protected function is_camel_case($word) {
      if (preg_match('/\p{L}(\p{Lu}*\p{Ll}\p{Ll}*\p{Lu}|\p{Ll}*\p{Lu}\p{Lu}*\p{Ll})\p{L}*/', $word)) {
        return true;
      }
      return false;
    }
  
    # ucfirst words split by dashes or periods
    # ucfirst all upper/lower strings, but leave camelcase words alone
    public function fix_case($word) {
  
      # Fix case for words split by periods (J.P.)
      if (mb_strpos($word, '.') !== false) {
        $word = $this->safe_ucfirst(".", $word);;
      }
  
      # Fix case for words split by hyphens (Kimura-Fay)
      if (mb_strpos($word, '-') !== false) {
        $word = $this->safe_ucfirst("-", $word);
      }
  
      # Special case for single letters
      if (mb_strlen($word) == 1) {
        $word = mb_strtoupper($word);
      }
  
      # Special case for 2-letter words
      if (mb_strlen($word) == 2) {
        # Both letters vowels (uppercase both)
        if (in_array(mb_strtolower($word[0]), $this->dict['vowels']) && in_array(mb_strtolower($word[1]), $this->dict['vowels'])) {
          $word = mb_strtoupper($word);
        }
        # Both letters consonants (uppercase both)
        if (!in_array(mb_strtolower($word[0]), $this->dict['vowels']) && !in_array(mb_strtolower($word[1]), $this->dict['vowels'])) {
          $word = mb_strtoupper($word);
        }
        # First letter is vowel, second letter consonant (uppercase first)
        if (in_array(mb_strtolower($word[0]), $this->dict['vowels']) && !in_array(mb_strtolower($word[1]), $this->dict['vowels'])) {
          $word = $this->mb_ucfirst(mb_strtolower($word));
        }
        # First letter consonant, second letter vowel or "y" (uppercase first)
        if (!in_array(mb_strtolower($word[0]), $this->dict['vowels']) && (in_array(mb_strtolower($word[1]), $this->dict['vowels']) || mb_strtolower($word[1]) == 'y')) {
          $word = $this->mb_ucfirst(mb_strtolower($word));
        }
      }
  
      # Fix case for words which aren't initials, but are all uppercase or lowercase
      if ( (mb_strlen($word) >= 3) && ($this->mb_ctype_upper($word) || $this->mb_ctype_lower($word)) ) {
        $word = $this->mb_ucfirst(mb_strtolower($word));
      }
  
      return $word;
    }
  
    # helper public function for fix_case
    public function safe_ucfirst($seperator, $word) {
      # uppercase words split by the seperator (ex. dashes or periods)
      $parts = explode($seperator, $word);
      foreach ($parts as $word) {
        $words[] = ($this->is_camel_case($word)) ? $word : $this->mb_ucfirst(mb_strtolower($word));
      }
      return implode($seperator, $words);
    }
  
      # helper public function for multibytes ctype_alpha
      public function mb_ctype_alpha($text)
      {
        return (bool)preg_match('/^\p{L}*$/', $text);
      }
  
      # helper public function for multibytes ctype_lower
      public function mb_ctype_lower($text)
      {
        return (bool)preg_match('/^\p{Ll}*$/', $text);
      }
  
      # helper public function for multibytes ctype_upper
      public function mb_ctype_upper($text)
      {
        return (bool)preg_match('/^\p{Lu}*$/', $text);
      }
  
      # helper public function for multibytes str_word_count
      public function mb_str_word_count($text)
      {
        if (empty($text)) {
          return 0;
        }
  
        $matchesCount = preg_match_all('/\s+/', $text);
  
        if (!$matchesCount) {
          return 1;
        }
        
        return $matchesCount + 1;
      }
  
      # helper public function for multibytes ucfirst
      public function mb_ucfirst($string)
      {
        $strlen = mb_strlen($string);
        $firstChar = mb_substr($string, 0, 1);
        $then = mb_substr($string, 1, $strlen - 1);
        return mb_strtoupper($firstChar) . $then;
      }
}
