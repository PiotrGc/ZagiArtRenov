<?php
// Filtre des avis : termes injurieux, racistes, homophobes ou sexuels, en plusieurs langues.
// Ce fichier ne contient AUCUN secret : il est versionné.
//
// Règles :
// - Les mots s'écrivent en minuscules, SANS accents (le texte est normalisé de la même façon).
// - Un mot n'est trouvé qu'entier (« con » ne bloque donc pas « conseil »), avec un pluriel
//   ou un féminin simple (s, e, es, x). Les lettres répétées (« connnnard ») et les chiffres
//   à la place des lettres (« c0nn4rd ») sont détectés, ainsi que les lettres espacées (« p u t e »).
// - Ne pas ajouter de mots qui servent à critiquer une prestation (« arnaque », « escroc »,
//   « nul »...) : un avis ne doit jamais être refusé parce qu'il est négatif
//   (art. L111-7-2 du Code de la consommation, rappelé sur la page d'accueil).
// - Éviter les mots courants dans une autre langue ou ambigus (« con » = « avec » en espagnol,
//   « retard » = délai en français, « trainee » = stagiaire en anglais,
//   « bitte » = « s'il vous plaît » en allemand...) : l'avis légitime serait refusé.

const MOTS_INTERDITS = [
    // Français : insultes
    'connard', 'connasse', 'conasse', 'quel con', 'gros con', 'pauvre con', 'sale con', 'espece de con',
    'salope', 'salaud', 'pute', 'putain de ta mere', 'fils de pute', 'fdp', 'ntm', 'nique ta mere',
    'nique ta race', 'niquer', 'nique', 'encule', 'enculer', 'enfoire', 'batard', 'abruti', 'cretin',
    'debile', 'imbecile', 'attarde', 'mongol', 'gogol', 'triso', 'trisomique', 'branleur', 'trou du cul',
    'ta gueule', 'ferme ta gueule', 'va te faire foutre', 'va te faire enculer', 'grosse pute',
    'pouffiasse', 'catin',
    // Français : homophobie / sexisme
    'pd', 'pede', 'tapette', 'tarlouze', 'tafiole', 'gouine', 'fiotte',
    // Français : racisme / antisémitisme
    'negre', 'negresse', 'negro', 'bamboula', 'bougnoule', 'bougnoul', 'bicot', 'raton', 'crouille',
    'youpin', 'youtre', 'chinetoque', 'niakoue', 'sale arabe', 'sale noir', 'sale juif', 'sale race',
    'sale chinois', 'sale gitan', 'sale blanc', 'sale musulman', 'sale noire', 'sale bougnoule',
    'retourne dans ton pays', 'heil hitler', 'sieg heil',

    // Anglais
    'fuck', 'fucking', 'fucker', 'motherfucker', 'fuck you', 'bitch', 'cunt', 'whore', 'slut',
    'bastard', 'asshole', 'dumbass', 'retarded', 'nigger', 'nigga', 'negroes', 'faggot',
    'fag', 'dyke', 'tranny', 'chink', 'gook', 'spic', 'kike', 'wetback', 'paki', 'coon', 'raghead',
    'sand nigger', 'son of a bitch',

    // Espagnol
    'puta', 'puto', 'hijo de puta', 'cabron', 'gilipollas', 'pendejo', 'maricon', 'zorra', 'imbecil',
    'sudaca', 'negrata', 'mamon', 'chinga tu madre', 'hijueputa',

    // Italien
    'cazzo', 'stronzo', 'stronza', 'vaffanculo', 'puttana', 'troia', 'frocio', 'coglione',
    'figlio di puttana', 'terrone',

    // Allemand
    'arschloch', 'hurensohn', 'fotze', 'wichser', 'schlampe', 'missgeburt', 'kanake', 'neger',
    'schwuchtel', 'spast', 'fick dich', 'scheisskerl',

    // Portugais
    'caralho', 'filho da puta', 'viado', 'buceta', 'otario', 'vai tomar no cu', 'arrombado',

    // Néerlandais
    'klootzak', 'hoer', 'mongool', 'kutwijf',

    // Polonais
    'kurwa', 'chuj', 'spierdalaj', 'jebac', 'pierdol', 'ciota', 'skurwysyn',

    // Serbe / croate / bosnien (alphabet latin, sans accents)
    'picka', 'pizda', 'jebem', 'jebi se', 'jebote', 'kurac', 'kurva', 'peder', 'picku materinu',
    'jebem ti mater', 'drkadzija', 'svinjo',

    // Serbe / russe / ukrainien (alphabet cyrillique)
    'пичка', 'пизда', 'јебем', 'курац', 'курва', 'педер', 'блядь', 'бляд', 'сука', 'хуй', 'пидор',
    'пидорас', 'мудак',

    // Roumain
    'pula', 'muie', 'futu-ti', 'curva',

    // Turc
    'orospu', 'orospu cocugu', 'siktir', 'yarrak', 'ibne', 'amina koyim',

    // Arabe (translittéré, y compris l'argot courant en France) et alphabet arabe
    'zebi', 'kahba', 'kahb', 'charmouta', 'sharmouta', 'zamel', 'nik mok', 'nik yemak', 'tahan',
    'شرموطة', 'قحبة', 'كس امك', 'زبي', 'منيوك', 'خول',
];

// Met le texte sous une forme comparable à la liste : minuscules, sans accents,
// chiffres « leet » remplacés, lettres isolées recollées (« p u t e » -> « pute »).
function texte_normalise(string $texte): string
{
    $texte = mb_strtolower($texte, 'UTF-8');
    $texte = strtr($texte, [
        'à' => 'a', 'â' => 'a', 'ä' => 'a', 'á' => 'a', 'ã' => 'a', 'å' => 'a',
        'ç' => 'c', 'č' => 'c', 'ć' => 'c',
        'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e', 'ę' => 'e',
        'î' => 'i', 'ï' => 'i', 'í' => 'i', 'ì' => 'i', 'ı' => 'i',
        'ô' => 'o', 'ö' => 'o', 'ó' => 'o', 'ò' => 'o', 'õ' => 'o', 'ø' => 'o',
        'û' => 'u', 'ü' => 'u', 'ú' => 'u', 'ù' => 'u',
        'ñ' => 'n', 'ń' => 'n', 'ś' => 's', 'š' => 's', 'ş' => 's', 'ž' => 'z', 'ź' => 'z', 'ż' => 'z',
        'đ' => 'd', 'ł' => 'l', 'ğ' => 'g', 'ß' => 'ss', 'œ' => 'oe', 'æ' => 'ae',
        '0' => 'o', '1' => 'i', '3' => 'e', '4' => 'a', '5' => 's', '7' => 't',
        '@' => 'a', '$' => 's', '€' => 'e', '!' => 'i',
    ]);
    // Lettres isolées séparées par des espaces ou de la ponctuation : on les recolle.
    return preg_replace('/(?<![\p{L}\p{N}])(\p{L})[\s.\-_*]+(?=\p{L}(?![\p{L}\p{N}]))/u', '$1', $texte) ?? $texte;
}

// Expression régulière construite une seule fois à partir de la liste.
function motif_mots_interdits(): string
{
    static $motif = null;
    if ($motif === null) {
        $variantes = [];
        foreach (MOTS_INTERDITS as $mot) {
            $parties = [];
            foreach (preg_split('//u', $mot, -1, PREG_SPLIT_NO_EMPTY) as $car) {
                // Chaque lettre peut être répétée ; les espaces acceptent toute ponctuation.
                $parties[] = $car === ' ' ? '[\s\p{P}]+' : preg_quote($car, '/') . '+';
            }
            $variantes[] = implode('', $parties);
        }
        $motif = '/(?<![\p{L}\p{N}])(?:' . implode('|', $variantes) . ')(?:s|e|es|x)?(?![\p{L}\p{N}])/u';
    }
    return $motif;
}

// Renvoie les termes interdits trouvés dans le texte (tableau vide si le texte est correct).
function mots_interdits_trouves(string $texte): array
{
    // Texte non UTF-8 : les expressions régulières échoueraient sans rien trouver.
    // On le considère comme suspect plutôt que de le laisser passer.
    if (!mb_check_encoding($texte, 'UTF-8')) {
        return ['(texte illisible)'];
    }
    $resultat = preg_match_all(motif_mots_interdits(), texte_normalise($texte), $trouves);
    if ($resultat === false) {
        return ['(texte illisible)'];
    }
    return $resultat ? array_values(array_unique($trouves[0])) : [];
}
