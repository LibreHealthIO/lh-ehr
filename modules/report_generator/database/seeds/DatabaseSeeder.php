<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(AddressTableSeeder::class);
        $this->call(ContactTableSeeder::class);
        $this->call(FacilityTableSeeder::class);
        $this->call(PatientDataTableSeeder::class);
        $this->call(PatientContactCommunicationTableSeeder::class);
        $this->call(PatientContactTableSeeder::class);
        $this->call(PatientEmployerTableSeeder::class);
        $this->call(PatientFaceSheetTableSeeder::class);
        $this->call(PatientPrivacyContactTableSeeder::class);
        $this->call(PatientSocialStatisticTableSeeder::class);
        $this->call(UserAddrBookTableSeeder::class);
        $this->call(UserCommunicationTableSeeder::class);
        $this->call(UserFacilityLinkTableSeeder::class);
        $this->call(UserPasswordHistoryTableSeeder::class);
        $this->call(UserResidentialTableSeeder::class);
        $this->call(UserSecureTableSeeder::class);
        $this->call(UserSettingTableSeeder::class);
        $this->call(X12PartnerTableSeeder::class);
    }
}

