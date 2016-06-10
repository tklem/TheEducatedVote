<?php
class Constants
{
    private static $Issuekeys = []; //all the keys for the different issues
    private static $patronIssuesCookieName = ''; //name of the cookie where we store (in order) the issues that are important to the patron
    private static $patronBasicInfoKeys = [];
    private static $documentRoot = ''; //directory string that gets us to this project, in case it ever changes
    private static $serverRoot = '';
    private static $websiteName ='';
    private static $candidates_basic_info_keys = [];
    private static $candidates_contact_info_keys = [];
    private static $candidates_committees_and_other_responsibilites_keys = [];
    private static $candidates_held_offices_keys = [];
    private static $candidates_offices_info_keys = [];
    private static $issueDefinitionKeys = [];
    
    private static $constant_Instance; //instance of this class to be returned
    
    private function __construct() //give constants their values
    {
        self::$Issuekeys = ['abortion',
            'affirmative_action',
            'birth_control',
            'campaign_finance',
            'capital_gains_taxes',
            'civil_rights',
            'climate_change',
            'corporate_income_taxes',
            'death_penalty',
            'digital_privacy',
            'drug_decriminalization',
            'education_funding',
            'equal_pay',
            'euthanasia',
            'farm_subsidies',
            'foreign_aid',
            'foreign_policy_middle_east',
            'fracking',
            'gerrymandering',
            'government_debt',
            'government_lobbying',
            'government_spending',
            'green_energy',
            'guantanamo_bay',
            'gun_control',
            'health_care_reform',
            'immigration_latin_america',
            'income_inequality',
            'income_taxes',
            'infrastructure_funding',
            'israel',
            'lgbtq',
            'marijuana_legalization',
            'medicaid',
            'military_drones',
            'military_spending',
            'minimum_wage',
            'nasa_funding',
            'net_neutrality',
            'nsa_surveillance',
            'nuclear_disarmament',
            'offshore_banking',
            'oil_industry',
            'pension_reform',
            'planned_parenthood',
            'pollution',
            'property_taxes',
            'refugee_crisis',
            'religious_freedom',
            'scientific_funding',
            'sick_and_maternity_leave',
            'social_security',
            'term_limits',
            'terrorism',
            'torture',
            'unions',
            'vaccinations',
            'voter_id_laws',
            'wall_street_regulations',
            'war_on_isis',
            'welfare',
            'women_in_combat',
        ];
        self::$patronIssuesCookieName = "patronIssuesOrdered";
        self::$patronBasicInfoKeys = [
            'id' => 'id',
            'name' => 'name',
            'login' => 'login',
            'password' => 'password',
            'address_line_one' => 'address_line_one',
            'address_line_two' => 'address_line_two',
            'city' => 'city',
            'state' => 'state',
            'zipcode' => 'zipcode',
            'email' => 'email',
            'donation_amount' => 'donation_amount'
        ];
        self::$documentRoot = "C://Users/Kevin/PhpstormProjects/TheEducatedVote/";
        self::$websiteName = 'The Educated Vote';
        self::$candidates_basic_info_keys = [
            'id' => 'id',
            'name' => 'name',
            'party' => 'party',
            'election' => 'election',
            'office' => 'office',
            'state' => 'state',
            'district' => 'district',
            'district_code' => 'district_code',
            'thing_one' => 'thing_one',
            'thing_two' => 'thing_two',
            'thing_three' => 'thing_three',
            'bio' => 'bio'
        ];
        self::$candidates_committees_and_other_responsibilites_keys = [
            'id' => 'id',
            'held_offices_id_fk' => 'held_offices_id_fk',
            'title' => 'title',
            'rank' => 'rank',
            'start_date' => 'start_date',
            'end_date' => 'end_date',
        ];
        self::$candidates_contact_info_keys = [
            'id' => 'id',
            'email' => 'email',
            'website' => 'website',
            'facebook' => 'facebook',
            'twitter' => 'twitter',
            'instagram' => 'instagram',
            'youtube' => 'youtube',
            'google_plus' => 'google_plus',
            'flickr' => 'flickr',
            'tumblr' => 'tumblr'
        ];
        self::$candidates_held_offices_keys = [
            'id' => 'id',
            'title' => 'title',
            'state' => 'state',
            'start_date' => 'start_date',
            'end_date' => 'end_date'
        ];
        self::$candidates_offices_info_keys = [
            'id' => 'id',
            'phone' => 'phone',
            'address_line_one' => 'address_line_one',
            'address_line_two' => 'address_line_two',
            'city' => 'city',
            'state' => 'state',
            'zipcode' => 'zipcode',
            'fax' => 'fax',
        ];
        self::$serverRoot = 'http://localhost:63342/';
        self::$issueDefinitionKeys = [
            'id' => 'id',
            'issue' => 'issue',
            'explanation' => 'explanation',
            'reference_1' => 'reference_1',
            'reference_2' => 'reference_2',
            'reference_3' => 'reference_3',
            'reference_4' => 'reference_4',
        ];
    }

    public static function getInstance() // Getter method for creating/returning the single instance of this class
    {
        if (!self::$constant_Instance)
        {
            self::$constant_Instance = new Constants();
        }
        return self::$constant_Instance;
    }
    
    public static function getIssueKeys(){return self::$Issuekeys;}
    
    public static function getPatronIssuesCookieName(){return self::$patronIssuesCookieName;}
    
    public static function getPatronBasicInfoKeys() {return self::$patronBasicInfoKeys;}
    
    public static function getDocumentRoot(){return self::$documentRoot;}
    
    public static function getWebsiteName(){return self::$websiteName;}

    public static function getCandidateBasicInfoKeys(){return self::$candidates_basic_info_keys;}

    public static function getCandidateCommitteesEtcKeys(){return self::$candidates_committees_and_other_responsibilites_keys;}

    public static function getCandidateContactInfoKeys(){return self::$candidates_contact_info_keys;}

    public static function getCandidateHeldOfficesKeys(){return self::$candidates_held_offices_keys;}

    public static function getCandidateOfficesInfoKeys(){return self::$candidates_offices_info_keys;}
    
    public static function getServerRoot(){return self::$serverRoot;}

    public static function getIssueDefinitionKeys(){return self::$issueDefinitionKeys;}
}
?>