<?php

class SudokuGrid implements GridInterface
{
    public array $data;

    //! Methode loadFromFile qui permet de charger le sudoku et de le décoder

    public static function loadFromFile($filepath): ?SudokuGrid
    {
        $contenu_fichier = file_get_contents($filepath);
        $data = json_decode($contenu_fichier, true);
        if ($data === null) {
            // echo "Erreur lors du décodage du fichier JSON.";
            return null;
        } else {
            return new SudokuGrid($data);
        }
    }

    //! Methode __construct qui perme d'instancier data pour pouvoir le réutiliser ailleurs

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    //! Methode get qui permet de lire une valeur à l'index donné

    public function get(int $rowIndex, int $columnIndex): int
    {
        $cell = $this->data[$rowIndex][$columnIndex];
        return $cell;
    }

    //! Methode set qui perme de mettre une valeur donnée à un endroit donné

    public function set(int $rowIndex, int $columnIndex, int $value): void
    {
        $this->data[$rowIndex][$columnIndex] = $value;
    }

    //! Methode row qui permet de lire les données d'une ligne

    public function row(int $rowIndex): array
    {
        return $this->data[$rowIndex];
    }

    //! Methode column qui permet de lire les données d'une colonne

    public function column(int $columnIndex): array
    {
        $column = [];
    
        foreach ($this->data as $row) {
            if (isset($row[$columnIndex])) {
                $column[] = $row[$columnIndex];
            }
        }
    
        return $column;
    }
    

    //! Methode square qui permet de lire les données d'un carré à partir d'un index donné

    public function square(int $squareIndex): array
    {
        $tailleBloc = 3;
        $ligneDebut = floor($squareIndex / $tailleBloc) * $tailleBloc;
        $colonneDebut = ($squareIndex % $tailleBloc) * $tailleBloc;

        $bloc = [];

        for ($i = $ligneDebut; $i < $ligneDebut + $tailleBloc; $i++) {
            for ($j = $colonneDebut; $j < $colonneDebut + $tailleBloc; $j++) {
                $bloc[] = $this->data[$i][$j];
            }
        }

        return $bloc;
    }

    //! Mehode de parcours de la grille

    public function display(): string
    {
        $output = "";
        foreach ($this->data as $row) {
            $output .= implode(" ", $row) . "\n";
        }
        return $output;
    }

    // //! Methode de vérification isValidForPosition

    public function isValueValidForPosition(int $rowIndex, int $columnIndex, int $value): bool
    {
        for ($i = 0; $i < 9; $i++) {
            if ($i != $columnIndex and $this->data[$rowIndex][$i] === $value) {
                return false;
            }
        }

        for ($i = 0; $i < 9; $i++) {
            if ($i != $rowIndex and $this->data[$i][$columnIndex] === $value) {
                return false;
            }
        }

        $startRow = intdiv($rowIndex, 3) * 3;
        $startCol = intdiv($columnIndex, 3) * 3;
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                if (($startRow + $i != $rowIndex or $startCol + $j != $columnIndex) and $this->data[$startRow + $i][$startCol + $j] === $value) {
                    return false;
                }
            }
        }
        return true;
    }

    public function getNextRowColumn(int $rowIndex, int $columnIndex): array{

        $columnIndex +=1;
    
        if($columnIndex>8){
            $columnIndex=0;
            $rowIndex += 1;
        }
    
        if($rowIndex>8){
            return [null,null];
        }
    
        return [$rowIndex,$columnIndex];
    
       }
    
    //! Méthode qui test si la valeur est valide
    public function isValid(): bool{
        for ($i = 0; $i < 9; $i += 3) {
            for ($j = 0; $j < 9; $j += 3) {
                $region = array();

                for ($k = 0; $k < 3; $k++) {
                    for ($l = 0; $l < 3; $l++) {
                        if (in_array($this->data[$i + $k][$j + $l], $region)) {
                            return false;
                        }
                        $region[] = $this->data[$i + $k][$j + $l];
                    }
                }
            }
        }

        return true;}

    //! Methode pour vérifier si la grille est complètement remplie
    public function isFilled(): bool
    {
        foreach ($this->data as $row) {
            if (in_array(0, $row)) {
                return false;
            }
        }
        return true;
    }
}
