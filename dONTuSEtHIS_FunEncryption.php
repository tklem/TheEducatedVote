<?php
class SmallScaleCypher
{
    private $fibonacciSingles;
    private $encryptionTable = []; //3D array of all the typable characters and a couple placeholders. Will use for encryption.
    private $vowelBank = [];
    private $consenantBank = [];

    private function __construct() //establish connection, limits object instantiation to within the class
    {
        $this->encryptionTable = //filling our encryption table, based on the layout of a standard qwerty keyboard with some ad-libbing in the bottom right corner
        [
            [
                ['z', 'Z'],
                ['x', 'X'],
                ['c', 'C'],
                ['v', 'V'],
                ['b', 'B'],
                ['n', 'N'],
                ['m', 'M'],
                [',', '<'],
                ['.', '>'],
                ['/', '?'],
                [chr(127), ' '],
                ['`', '~']
            ],
            [
                ['a', 'A'],
                ['s', 'S'],
                ['d', 'D'],
                ['f', 'F'],
                ['g', 'G'],
                ['h', 'H'],
                ['j', 'J'],
                ['k', 'K'],
                ['l', 'L'],
                [';', ':'],
                ["'", '"'],
                ['\\', '|']
            ],
            [
                ['q', 'Q'],
                ['w', 'W'],
                ['e', 'E'],
                ['r', 'R'],
                ['t', 'T'],
                ['y', 'Y'],
                ['u', 'U'],
                ['i', 'I'],
                ['o', 'O'],
                ['p', 'P'],
                ['[', '{'],
                [']', '}']
            ],
            [
                ['1', '!'],
                ['2', '@'],
                ['3', '#'],
                ['4', '$'],
                ['5', '%'],
                ['6', '^'],
                ['7', '&'],
                ['8', '*'],
                ['9', '('],
                ['0', ')'],
                ['-', '_'],
                ['=', '+']
            ]
        ];
        $this->vowelBank = ['a', 'e', 'i', 'o', 'u', 'y', 'A', 'E', 'I', 'O', 'U', 'Y']; //length = 12
        $this->consenantBank = ['b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'z', 'B', 'C', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'V', 'W', 'X', 'Z']; //length = 40
        $this->fibonacciSingles = [1, 1, 2, 3, 5, 8, 3, 1, 4, 5, 9, 4, 3, 7, 0, 7, 7, 4, 1, 5, 6, 1, 7, 8, 5, 3, 8, 1, 9, 0, 9, 9, 8, 7, 5, 2, 7, 9, 6, 5, 1, 6, 7, 3, 0, 3, 3, 6, 9, 5, 4, 9, 3, 2, 5, 7, 2, 9, 1, 0, 1, 1, 2, 3, 5, 8, 3, 1, 4, 5, 9, 4, 3, 7, 0, 7, 7, 4, 1, 5, 6, 1, 7, 8, 5, 3, 8, 1, 9, 0, 9, 9, 8, 7, 5, 2, 7, 9, 6, 5]; //first 100 fibonacci ones digit numbers (f1 - f100)
    }
    
    public function smallEncrypt($encryptMe) //public method call to get the ball rolling with an encryption
    {
        $imEncryptedNow = $this->rowShift($encryptMe); //first step is the row shift
        return $imEncryptedNow;
    }

    //This row shift is a non-standard ASCII shift, meaning that it does not act arithmatically
    //Rather, it uses the layout of the qwerty keyboard, which is (in my estimate) harder to hack by conventional means
    //Since there is no numeric relation between a key's location relative to it's peers and any sort of ASCII or other
    //letter-to-numeral system. However, it is a simple char-to-char translation, so it's only the first step here.
    private function rowShift($encryptMe) //using our encryption table, bump everything up one row (or to the bottom if it's at the top)
    {
        $shiftedRowsAndColumns = []; //we'll use this to store the locations of the chars in encryptMe
        $encryptMeArray = str_split($encryptMe); //php doesn't make looping through strings fun, so we have to make it an array

        //I understand this is about to be a four dimensial for loop, but I promise it isn't that bad. at 50 chars, max total iterations is 4800, and that would be exquisitely shitty luck
        foreach($encryptMeArray as $letter) //Go through all the chars. max iterations: Length of encryptMe, maxing at around 50 characters
        {
            for($i = 0; $i < 12; $i++) //work our way through the encryption table. Max iterations: 12
            {
                for($j = 0; $j < 4; $j++) //max iterations: 4
                {
                    for($k = 0; $k < 2; $k++) //max iterations: 2
                    {
                        if($letter == $this->encryptionTable[$i][$j][$k]) //if our letter is found
                        {
                            if($j == count($this->encryptionTable[0]) - 1) //if it's in the top row
                                $shiftedRowsAndColumns[] = [$i, 0, $k]; //we bump it to the bottom
                            else                                        //otherwise
                                $shiftedRowsAndColumns[] = [$i, $j+1, $k]; //bump it up one row
                        }
                    }
                }
            }
        }

        return $this->rowColumnTransformation($shiftedRowsAndColumns);
    }

    //Here's where things get interesting.
    //We again utilize the table of characters that we initially used for the char-to-char translation
    //This time, we are moving from chars to coordinates - the coordinates of the letter in the table, a 3-dimensional array
    //Coordinates are easily mapped back to their locations, however, so here we take measures to make hackers' lives difficult.
    //Each dimension of the coordinate is represented uniquely.
    //The first, for the row, begins with a random vowel (not case sensitive) and is followed by two numeric digits
    //The vowel is inconsequential. The two-digit number will be divisible by one of 3, 2, 1, or 0 (assuming 0 is divisible by 0)
    //Which maps the two-digit number to the corresponding row.
    //The second dimension is the column, whose numeric value is represented by the number of digits placed after the row number
    //And followed by a random consenant (again, inconsequential). Column 6 then might be 729467F, column 4 might be 1112h, and so on.
    //This has the benefit of throwing off any hack attempt to map keys to hashed values by means of char-to-char or chunk-to-char methods.
    //The third dimension is SHIFT status, and has only two states, upper and lower.
    //Lowercased chars will be represented by even, random, single digits and uppercased chars by odds.
    private function rowColumnTransformation($shiftedRowsAndColumns)
    {
        $encryptString = ''; //will hold new encrypted string
        $tempRand = 0; //temp storage
        foreach($shiftedRowsAndColumns as $curr) //go through each character as a set of coordinates
        {
            $encryptString .= $this->vowelBank[random_int(0, 11)]; //starts with a vowel

            if($curr[0] == 0) //if row is zero, the second and third digits are zero
                $encryptString .= "00";
            else //if not
            {
                do
                {
                    $tempRand = random_int(10, 99);
                } while ($this->checkForRowPrime($tempRand, $curr)); //@@@@@implement@@@@@ //generate a random 2 digit number that is only divisible by the row number
                $encryptString .= $tempRand;
            }
            for ($i = 0; $i < $curr[1]; $i++) //generate a number of random numbers equal to the column number
            {
                $encryptString .= random_int(0, 9);
            }

            $encryptString .= $this->consenantBank[random_int(0,39)]; //adds a consenant to end the column

            //dealing with SHIFT
            if($curr[2] == 0)  //if lower
            {
                $tempRand = random_int(1, 9);
                if($tempRand % 2 == 1) //need even number
                    $tempRand -= 1;
            }
            else //if upper
            {
                $tempRand = random_int(2, 9);
                if($tempRand % 2 == 0) //need odd number
                    $tempRand -= 1;
            }
            $encryptString .= $tempRand;
        }

        return $this->fibonacciScramble($encryptString);
    }

    //In case anyone gets through all of that, we have one more trick up our sleeve, courtesy of our favorite Mr. Fibonacci.
    //An array in this file contains the first (currently 100) ones-digit values for the Fibonacci sequence.
    //While these numbers certainly have a pattern, using only the ones digits of the well-known sequence is both more efficient and slightly sneakier.
    //The function uses the numbers to take the last transformation and scramble it up in a seemingly chaotic way.
    //Using the sequence, the function takes numbers alternatingly working forwards from the start and backwards from the end
    //Of the string, using the fib sequence to know how many to take, and then placing them in a new string. We'll use the string
    //HelloItIsI,LeonardDiCapricorn for an example. First we take 1 letter from the front and one from the back, so 'Hn', then again
    //1 from the front and back, so 'Hner', then 2 (when taking from the back, read backwards, don't just take the back chunk in order), getting
    //'Hnerlloc' then 3 'HnerllocoItirp' and so on until all the letters are taken.
    private function fibonacciScramble($encryptString)
    {
        $encryptStringArray = str_split($encryptString); //array of our string
        $scrambled = ''; //to hold our scrambled encryption
        $fromFrontIndex = 0; //starts with the first char
        $fromBackIndex = strlen($encryptString) - 1; //starts with the last char
        $fibIndex = 0; //moves through fibonacci array

        while($fromFrontIndex != $fromBackIndex) //while we haven't met in the middle yet 
        {
            $currFib = $this->fibonacciSingles[$fibIndex]; //get a fib 
            while($currFib > 0 && $fromFrontIndex != $fromBackIndex) //forward crawl, for fib number of chars, unless we meet in the middle  
            {
                $scrambled .= $encryptStringArray[$fromFrontIndex]; 
                $fromFrontIndex += 1; $currFib -= 1;
            }
            
            while($currFib > 0 && $fromFrontIndex != $fromBackIndex)//backwards crawl, for fib number of chars, unless we meet in the middle
            {
                $scrambled .= $encryptStringArray[$fromBackIndex];
                $fromFrontIndex -= 1; $currFib -= 1;
            }
            $fibIndex += 1;
        }
        return $scrambled;
    }

    private function checkForRowPrime($tempRand, $dims) //@@@@@implement@@@@@
    {
        return 1;
    }


    public function smallDecode($decodeMe) //public method call to start the decoding process
    {
        $imDecodedNow = $this->undoFibonacciScramble($decodeMe);
        return $imDecodedNow;
    }

    private function undoFibonacciScramble($decodeMe) //Put the hashed string back in order
    {
        return $this->undoRowColumnTransformation($decodeMe);
    }

    private function undoRowColumnTransformation($decodeMe) //Translate chunks back into coordinates of chars
    {
        return $this->undoRowShift($decodeMe);
    }

    private function undoRowShift($decodeMe) //Bump every char down one row to get the original value
    {
        return $decodeMe;
    }
}
?>









