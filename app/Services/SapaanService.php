<?php

namespace App\Services;

use App\Models\FamilyMember;
use App\Models\Marga;
use Illuminate\Support\Collection;

class SapaanService
{
    /**
     * Get the appropriate sapaan (term of address) from one family member to another
     *
     * @param FamilyMember $from The person who is addressing
     * @param FamilyMember $to The person being addressed
     * @return string The appropriate sapaan term
     */
    public function getSapaan(FamilyMember $from, FamilyMember $to): string
    {
        // If same person, return self-reference
        if ($from->id === $to->id) {
            return "Diri sendiri";
        }

        // Detect relationship
        $relationship = $this->detectRelationship($from, $to);
        
        // Classify based on Dalihan Na Tolu
        $dalihanNaToluCategory = $this->classifyDalihanNaTolu($from, $to, $relationship);
        
        // Determine sapaan
        $sapaan = $this->determineSapaan($from, $to, $relationship, $dalihanNaToluCategory);
        
        return $sapaan;
    }
    
    /**
     * Detect the relationship between two family members
     *
     * @param FamilyMember $from
     * @param FamilyMember $to
     * @return string
     */
    protected function detectRelationship(FamilyMember $from, FamilyMember $to): string
    {
        // Check direct relationships
        
        // Parent-child relationships
        if ($from->father_id === $to->id) {
            return "father";
        }
        
        if ($from->mother_id === $to->id) {
            return "mother";
        }
        
        if ($to->father_id === $from->id) {
            return $to->gender === "male" ? "son" : "daughter";
        }
        
        if ($to->mother_id === $from->id) {
            return $to->gender === "male" ? "son" : "daughter";
        }
        
        // Spouse relationship
        $isSpouse = $from->spouses()->where('family_members.id', $to->id)->exists();
        if ($isSpouse) {
            return $to->gender === "male" ? "husband" : "wife";
        }
        
        // Sibling relationships (same parents)
        $isSibling = ($from->father_id && $from->father_id === $to->father_id) || 
                     ($from->mother_id && $from->mother_id === $to->mother_id);
        
        if ($isSibling) {
            // Determine older or younger
            $fromBirthDate = $from->birth_date ?? now();
            $toBirthDate = $to->birth_date ?? now();
            
            if ($toBirthDate < $fromBirthDate) { // To is older
                return $to->gender === "male" ? "older_brother" : "older_sister";
            } else {
                return $to->gender === "male" ? "younger_brother" : "younger_sister";
            }
        }
        
        // Grandparent relationships
        if ($from->father && $from->father->father_id === $to->id) {
            return "paternal_grandfather";
        }
        
        if ($from->father && $from->father->mother_id === $to->id) {
            return "paternal_grandmother";
        }
        
        if ($from->mother && $from->mother->father_id === $to->id) {
            return "maternal_grandfather";
        }
        
        if ($from->mother && $from->mother->mother_id === $to->id) {
            return "maternal_grandmother";
        }
        
        // Grandchild relationships
        $isGrandchild = false;
        
        // Check if 'to' is a child of any of 'from's children
        $fromChildren = FamilyMember::where('father_id', $from->id)
            ->orWhere('mother_id', $from->id)
            ->get();
            
        foreach ($fromChildren as $child) {
            if ($to->father_id === $child->id || $to->mother_id === $child->id) {
                $isGrandchild = true;
                break;
            }
        }
        
        if ($isGrandchild) {
            return "grandchild";
        }
        
        // Uncle/Aunt relationships
        
        // Paternal uncle (father's brother)
        if ($from->father && $to->gender === "male" && 
            (($from->father->father_id && $from->father->father_id === $to->father_id) || 
             ($from->father->mother_id && $from->father->mother_id === $to->mother_id))) {
            
            // Determine if older or younger than father
            $fatherBirthDate = $from->father->birth_date ?? now();
            $toBirthDate = $to->birth_date ?? now();
            
            if ($toBirthDate < $fatherBirthDate) {
                return "paternal_uncle_older";
            } else {
                return "paternal_uncle_younger";
            }
        }
        
        // Paternal aunt (father's sister)
        if ($from->father && $to->gender === "female" && 
            (($from->father->father_id && $from->father->father_id === $to->father_id) || 
             ($from->father->mother_id && $from->father->mother_id === $to->mother_id))) {
            return "paternal_aunt";
        }
        
        // Maternal uncle (mother's brother)
        if ($from->mother && $to->gender === "male" && 
            (($from->mother->father_id && $from->mother->father_id === $to->father_id) || 
             ($from->mother->mother_id && $from->mother->mother_id === $to->mother_id))) {
            return "maternal_uncle";
        }
        
        // Maternal aunt (mother's sister)
        if ($from->mother && $to->gender === "female" && 
            (($from->mother->father_id && $from->mother->father_id === $to->father_id) || 
             ($from->mother->mother_id && $from->mother->mother_id === $to->mother_id))) {
            return "maternal_aunt";
        }
        
        // Nephew/Niece relationships
        $isNephewNiece = false;
        
        // Check if 'from' is a sibling of either of 'to's parents
        if ($to->father) {
            $isSiblingOfFather = ($from->father_id && $from->father_id === $to->father->father_id) || 
                                 ($from->mother_id && $from->mother_id === $to->father->mother_id);
            if ($isSiblingOfFather) {
                $isNephewNiece = true;
            }
        }
        
        if (!$isNephewNiece && $to->mother) {
            $isSiblingOfMother = ($from->father_id && $from->father_id === $to->mother->father_id) || 
                                 ($from->mother_id && $from->mother_id === $to->mother->mother_id);
            if ($isSiblingOfMother) {
                $isNephewNiece = true;
            }
        }
        
        if ($isNephewNiece) {
            return $to->gender === "male" ? "nephew" : "niece";
        }
        
        // Cousin relationships
        $isCousinPaternal = false;
        $isCousinMaternal = false;
        $isCousinCrossPaternal = false;
        $isCousinCrossMaternal = false;
        
        // Paternal cousins (father's siblings' children)
        if ($from->father && $to->father) {
            $areFathersSiblings = ($from->father->father_id && $from->father->father_id === $to->father->father_id) || 
                                  ($from->father->mother_id && $from->father->mother_id === $to->father->mother_id);
            if ($areFathersSiblings) {
                $isCousinPaternal = true;
            }
        }
        
        // Maternal cousins (mother's siblings' children)
        if ($from->mother && $to->mother) {
            $areMothersSiblings = ($from->mother->father_id && $from->mother->father_id === $to->mother->father_id) || 
                                  ($from->mother->mother_id && $from->mother->mother_id === $to->mother->mother_id);
            if ($areMothersSiblings) {
                $isCousinMaternal = true;
            }
        }
        
        // Cross cousins (father's sisters' children or mother's brothers' children)
        if ($from->father && $to->mother) {
            $isFatherBrotherOfMother = ($from->father->father_id && $from->father->father_id === $to->mother->father_id) || 
                                       ($from->father->mother_id && $from->father->mother_id === $to->mother->mother_id);
            if ($isFatherBrotherOfMother) {
                $isCousinCrossPaternal = true;
            }
        }
        
        if ($from->mother && $to->father) {
            $isMotherSisterOfFather = ($from->mother->father_id && $from->mother->father_id === $to->father->father_id) || 
                                      ($from->mother->mother_id && $from->mother->mother_id === $to->father->mother_id);
            if ($isMotherSisterOfFather) {
                $isCousinCrossMaternal = true;
            }
        }
        
        if ($isCousinPaternal) {
            return "cousin_paternal";
        }
        
        if ($isCousinMaternal) {
            return "cousin_maternal";
        }
        
        if ($isCousinCrossPaternal) {
            return "cousin_cross_paternal";
        }
        
        if ($isCousinCrossMaternal) {
            return "cousin_cross_maternal";
        }
        
        // In-law relationships
        
        // Parents-in-law
        $spouse = $from->spouses()->first();
        if ($spouse) {
            if ($spouse->father_id === $to->id) {
                return "father_in_law";
            }
            
            if ($spouse->mother_id === $to->id) {
                return "mother_in_law";
            }
        }
        
        // Children-in-law
        $toSpouse = $to->spouses()->first();
        if ($toSpouse) {
            if ($toSpouse->father_id === $from->id || $toSpouse->mother_id === $from->id) {
                return $to->gender === "male" ? "son_in_law" : "daughter_in_law";
            }
        }
        
        // Siblings-in-law (spouse's siblings)
        if ($spouse) {
            $isSpouseSibling = ($spouse->father_id && $spouse->father_id === $to->father_id) || 
                               ($spouse->mother_id && $spouse->mother_id === $to->mother_id);
            if ($isSpouseSibling) {
                return $to->gender === "male" ? "brother_in_law_spouse_side" : "sister_in_law_spouse_side";
            }
        }
        
        // Siblings-in-law (siblings' spouses)
        $fromSiblings = FamilyMember::where(function($query) use ($from) {
                $query->where(function($q) use ($from) {
                    $q->where('father_id', $from->father_id)
                      ->whereNotNull('father_id');
                })->orWhere(function($q) use ($from) {
                    $q->where('mother_id', $from->mother_id)
                      ->whereNotNull('mother_id');
                });
            })
            ->where('id', '!=', $from->id)
            ->get();
            
        foreach ($fromSiblings as $sibling) {
            $isSiblingSpouse = $sibling->spouses()->where('family_members.id', $to->id)->exists();
            if ($isSiblingSpouse) {
                return $to->gender === "male" ? "brother_in_law_own_side" : "sister_in_law_own_side";
            }
        }
        
        // Special Batak relationship: Pariban
        $isPariban = $this->isPariban($from, $to);
        if ($isPariban) {
            return "pariban";
        }
        
        // If no specific relationship is detected
        return "no_direct_relationship";
    }
    
    /**
     * Check if two people have a pariban relationship (potential marriage partners in Batak culture)
     * Pariban is typically the relationship between a man and the daughter of his mother's brother
     * or between a woman and the son of her father's sister
     *
     * @param FamilyMember $person1
     * @param FamilyMember $person2
     * @return bool
     */
    protected function isPariban(FamilyMember $person1, FamilyMember $person2): bool
    {
        // Case 1: person1 is male, person2 is female
        // Check if person2's father is person1's maternal uncle
        if ($person1->gender === "male" && $person2->gender === "female" && $person1->mother && $person2->father) {
            $isMotherSiblingWithFather = ($person1->mother->father_id && $person1->mother->father_id === $person2->father->father_id) || 
                                         ($person1->mother->mother_id && $person1->mother->mother_id === $person2->father->mother_id);
            if ($isMotherSiblingWithFather) {
                return true;
            }
        }
        
        // Case 2: person1 is female, person2 is male
        // Check if person2's mother is person1's paternal aunt
        if ($person1->gender === "female" && $person2->gender === "male" && $person1->father && $person2->mother) {
            $isFatherSiblingWithMother = ($person1->father->father_id && $person1->father->father_id === $person2->mother->father_id) || 
                                         ($person1->father->mother_id && $person1->father->mother_id === $person2->mother->mother_id);
            if ($isFatherSiblingWithMother) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Classify the relationship according to Dalihan Na Tolu concept
     *
     * @param FamilyMember $from
     * @param FamilyMember $to
     * @param string $relationship
     * @return string
     */
    protected function classifyDalihanNaTolu(FamilyMember $from, FamilyMember $to, string $relationship): string
    {
        // Hula-hula: Family that gives wife (in-laws from wife's side for a man)
        if (in_array($relationship, ["father_in_law", "mother_in_law", "brother_in_law_spouse_side", "sister_in_law_spouse_side"]) 
            && $from->gender === "male") {
            return "hula_hula";
        }
        
        // Boru: Family that receives wife (son-in-law's family)
        if (in_array($relationship, ["son_in_law", "daughter_in_law"])) {
            return "boru";
        }
        
        if (in_array($relationship, ["father_in_law", "mother_in_law"]) && $from->gender === "female") {
            return "boru";
        }
        
        // Dongan Tubu: Family of the same clan/marga
        if ($from->family_id === $to->family_id) {
            // Get marga information if available
            $fromMarga = $from->family->marga_id ?? null;
            $toMarga = $to->family->marga_id ?? null;
            
            if ($fromMarga && $toMarga && $fromMarga === $toMarga) {
                return "dongan_tubu";
            }
        }
        
        // Default if not in any specific category
        return "other";
    }
    
    /**
     * Determine the appropriate sapaan based on relationship and Dalihan Na Tolu category
     *
     * @param FamilyMember $from
     * @param FamilyMember $to
     * @param string $relationship
     * @param string $dalihanNaToluCategory
     * @return string
     */
    protected function determineSapaan(FamilyMember $from, FamilyMember $to, string $relationship, string $dalihanNaToluCategory): string
    {
        // Direct family relationships
        $sapaanMap = [
            // Core family
            "father" => "Amang",
            "mother" => "Inang",
            "older_brother" => "Abang",
            "older_sister" => "Kakak",
            "younger_brother" => "Anggi",
            "younger_sister" => "Anggi",
            "son" => "Anak",
            "daughter" => "Boru",
            "husband" => "Amang", // Often addressed as "father of [child's name]"
            "wife" => "Inang", // Often addressed as "mother of [child's name]"
            
            // Grandparents
            "paternal_grandfather" => "Ompung Doli",
            "paternal_grandmother" => "Ompung Boru",
            "maternal_grandfather" => "Ompung Doli",
            "maternal_grandmother" => "Ompung Boru",
            "grandchild" => "Pahompu",
            
            // Uncles and aunts
            "paternal_uncle_older" => "Amang Tua",
            "paternal_uncle_younger" => "Amang Uda",
            "paternal_aunt" => "Namboru",
            "maternal_uncle" => "Tulang",
            "maternal_aunt" => "Nantulang",
            
            // Nephews and nieces
            "nephew" => "Anak",
            "niece" => "Boru",
            
            // Cousins
            "cousin_paternal" => "Iboto", // Same clan cousins
            "cousin_maternal" => "Iboto",
            "cousin_cross_paternal" => "Pariban", // Cross cousins (potential marriage partners)
            "cousin_cross_maternal" => "Pariban",
            
            // In-laws
            "father_in_law" => $from->gender === "male" ? "Amang Boru" : "Tulang",
            "mother_in_law" => $from->gender === "male" ? "Inang Boru" : "Nantulang",
            "son_in_law" => "Hela",
            "daughter_in_law" => "Parumaen",
            "brother_in_law_spouse_side" => $from->gender === "male" ? "Lae" : "Tunggane",
            "sister_in_law_spouse_side" => $from->gender === "female" ? "Pariban" : "Eda",
            "brother_in_law_own_side" => $from->gender === "female" ? "Tunggane" : "Lae",
            "sister_in_law_own_side" => $from->gender === "male" ? "Eda" : "Pariban",
            
            // Special relationships
            "pariban" => "Pariban",
        ];
        
        // Return the appropriate sapaan if it exists in the map
        if (isset($sapaanMap[$relationship])) {
            return $sapaanMap[$relationship];
        }
        
        // If no specific sapaan is found, use Dalihan Na Tolu category
        $dalihanNaToluMap = [
            "hula_hula" => "Hula-hula",
            "boru" => "Boru",
            "dongan_tubu" => "Dongan Tubu",
        ];
        
        if (isset($dalihanNaToluMap[$dalihanNaToluCategory])) {
            return $dalihanNaToluMap[$dalihanNaToluCategory];
        }
        
        // Default sapaan based on gender if nothing else matches
        return $to->gender === "male" ? "Amang" : "Inang";
    }
}
