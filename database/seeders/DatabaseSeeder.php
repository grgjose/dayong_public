<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Usertype;
use App\Models\Branch;
use App\Models\Claimant;
use App\Models\Beneficiary;
use App\Models\Program;
use App\Models\Matrix;
use App\Models\Member;
use App\Models\MembersProgram;
use App\Models\Entry;
use App\Imports\DatabaseImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // =====================================================================
        // PART 1: Original Excel Seeder (Unchanged structure, uppercase applied)
        // =====================================================================

        $path = storage_path('app/public/imports/DatabaseSeeder.xlsx');

        $importer = new DatabaseImport();
        Excel::import($importer, $path);

        $data = $importer->getData();

        $usertypes = $data['Usertypes'];
        $users     = $data['Users'];
        $branches  = $data['Branches'];
        $programs  = $data['Programs'];
        $matrix    = $data['Matrix'];

        $usertypes_arr = [];
        $i = 1;

        foreach (array_slice($usertypes, 1) as $row) {
            if ($row[1] != null && $row[1] != '') {
                Usertype::factory()->create([
                    // usertype is a label/code — uppercase for consistency
                    'usertype' => strtoupper(trim($row[1])),
                ]);
                $usertypes_arr[$row[1]] = $i;
                $i++;
            }
        }

        foreach (array_slice($users, 1) as $row) {
            if ($row[1] != '' && $row[1] != null) {
                User::factory()->create([
                    'username'    => $row[1],                          // login credential — keep as-is
                    'usertype'    => $usertypes_arr[$row[2]],
                    'fname'       => strtoupper(trim($row[3])),
                    'mname'       => strtoupper(trim($row[4])),
                    'lname'       => strtoupper(trim($row[5])),
                    'ext'         => $row[6] ? strtoupper(trim($row[6])) : null,
                    'email'       => $row[7],                          // email — keep lowercase convention
                    'contact_num' => $row[8],
                    'address'     => strtoupper(trim($row[9])),
                    'birthdate'   => is_numeric($row[10])
                        ? Date::excelToDateTimeObject($row[10])->format('Y-m-d')
                        : null,
                    'password' => Hash::make($row[11]),
                    'status'   => $row[12],
                ]);
            }
        }

        foreach (array_slice($branches, 1) as $row) {
            if ($row[3] != '') {
                Branch::factory()->create([
                    'code'        => strtoupper(trim($row[1])),
                    'city'        => strtoupper(trim($row[2])),
                    'branch'      => strtoupper(trim($row[3])),
                    'address'     => strtoupper(trim($row[4])),
                    'description' => strtoupper(trim($row[5])),
                ]);
            }
        }

        foreach (array_slice($programs, 1) as $row) {
            if ($row[1] != '') {
                Program::factory()->create([
                    'code'               => strtoupper(trim($row[1])),
                    'description'        => strtoupper(trim($row[2])),
                    'beneficiaries_count'=> $row[3],
                    'age_min'            => $row[4],
                    'age_max'            => $row[5],
                    'ben_age_min'        => $row[6],
                    'ben_age_max'        => $row[7],
                    'term_min'           => $row[8],
                    'term_max'           => $row[9],
                    'amount_min'         => $row[10],
                    'amount_max'         => $row[11],
                    'status'             => $row[12],
                ]);
            }
        }

        // =====================================================================
        // PART 2: Test Data Seeding
        // =====================================================================

        // --- Resolve Baliok branch ---
        $baliokBranch = DB::table('branches')
            ->where('branch', 'BALIOK')   // uppercase to match seeded value
            ->first();

        if (!$baliokBranch) {
            $this->command->warn('Baliok branch not found. Make sure it exists in DatabaseSeeder.xlsx.');
            return;
        }

        // --- Resolve all programs (we will pick randomly) ---
        $allPrograms = DB::table('programs')->get();

        if ($allPrograms->isEmpty()) {
            $this->command->warn('No programs found. Make sure programs are seeded.');
            return;
        }

        // --- Resolve an Encoder (usertype = 2) ---
        $encoder = DB::table('users')->where('usertype', 2)->first();

        if (!$encoder) {
            $this->command->warn('No encoder (usertype=2) found. Please seed users first.');
            return;
        }

        // =====================================================================
        // Create 3 MAS Users (usertype = 3)
        // =====================================================================

        $masData = [
            [
                'fname'  => 'JOSE',
                'mname'  => 'REYES',
                'lname'  => 'DELA CRUZ',
                'email'  => 'jose.delacruz@gmail.com',
            ],
            [
                'fname'  => 'MARIA',
                'mname'  => 'SANTOS',
                'lname'  => 'GONZALES',
                'email'  => 'maria.gonzales@gmail.com',
            ],
            [
                'fname'  => 'RICARDO',
                'mname'  => 'BAUTISTA',
                'lname'  => 'VILLANUEVA',
                'email'  => 'ricardo.villanueva@gmail.com',
            ],
        ];

        $masUsers = [];

        foreach ($masData as $mas) {
            $user = User::create([
                'username'    => strtolower($mas['fname'][0] . $mas['lname']), // username stays lowercase
                'usertype'    => 3,
                'fname'       => strtoupper(trim($mas['fname'])),
                'mname'       => strtoupper(trim($mas['mname'])),
                'lname'       => strtoupper(trim($mas['lname'])),
                'email'       => $mas['email'],                                // email stays lowercase
                'contact_num' => '09' . rand(100000000, 999999999),
                'address'     => 'BALIOK, DAVAO CITY',
                'birthdate'   => Carbon::now()->subYears(rand(25, 40))->format('Y-m-d'),
                'branch_id'   => $baliokBranch->id,
                'profile_pic' => 'default.png',
                'password'    => Hash::make('password'),
                'status'      => 'active',
            ]);

            $masUsers[] = $user;
        }

        // =====================================================================
        // Member name pool (24 unique members, 8 per MAS)
        // =====================================================================

        $memberPool = [
            // MAS 1 members
            ['fname'=>'ANTONIO',  'mname'=>'LUNA',      'lname'=>'REYES',      'sex'=>'MALE'],
            ['fname'=>'CAROLINA', 'mname'=>'MENDOZA',   'lname'=>'GARCIA',     'sex'=>'FEMALE'],
            ['fname'=>'FERNANDO', 'mname'=>'RAMOS',     'lname'=>'TORRES',     'sex'=>'MALE'],
            ['fname'=>'GLORIA',   'mname'=>'SANTOS',    'lname'=>'FLORES',     'sex'=>'FEMALE'],
            ['fname'=>'HERMINIO', 'mname'=>'CRUZ',      'lname'=>'MEDINA',     'sex'=>'MALE'],
            ['fname'=>'ISABELITA','mname'=>'BAUTISTA',  'lname'=>'AQUINO',     'sex'=>'FEMALE'],
            ['fname'=>'JUAN',     'mname'=>'DELA PENA', 'lname'=>'SANTIAGO',   'sex'=>'MALE'],
            ['fname'=>'KRISTINA', 'mname'=>'VALDEZ',    'lname'=>'CASTILLO',   'sex'=>'FEMALE'],
            // MAS 2 members
            ['fname'=>'LORENZO',  'mname'=>'FERNANDEZ', 'lname'=>'SORIANO',    'sex'=>'MALE'],
            ['fname'=>'MELINDA',  'mname'=>'DIAZ',      'lname'=>'PANGANIBAN', 'sex'=>'FEMALE'],
            ['fname'=>'NESTOR',   'mname'=>'PASCUAL',   'lname'=>'AGUILAR',    'sex'=>'MALE'],
            ['fname'=>'OFELIA',   'mname'=>'GUERRERO',  'lname'=>'MIRANDA',    'sex'=>'FEMALE'],
            ['fname'=>'PEDRO',    'mname'=>'SALAZAR',   'lname'=>'NAVARRO',    'sex'=>'MALE'],
            ['fname'=>'QUIRINA',  'mname'=>'MOLINA',    'lname'=>'HERRERA',    'sex'=>'FEMALE'],
            ['fname'=>'RODRIGO',  'mname'=>'ESPIRITU',  'lname'=>'DELA TORRE', 'sex'=>'MALE'],
            ['fname'=>'SOLEDAD',  'mname'=>'IGNACIO',   'lname'=>'CAMACHO',    'sex'=>'FEMALE'],
            // MAS 3 members
            ['fname'=>'TIMOTEO',  'mname'=>'ABAD',      'lname'=>'FUENTES',    'sex'=>'MALE'],
            ['fname'=>'URSULA',   'mname'=>'CONCEPCION','lname'=>'LOZANO',     'sex'=>'FEMALE'],
            ['fname'=>'VIRGILIO', 'mname'=>'DOMINGO',   'lname'=>'CEDENO',     'sex'=>'MALE'],
            ['fname'=>'WILHELMINA','mname'=>'ESPINOSA', 'lname'=>'BLANCO',     'sex'=>'FEMALE'],
            ['fname'=>'XAVIER',   'mname'=>'FRANCISCO', 'lname'=>'CORONEL',    'sex'=>'MALE'],
            ['fname'=>'YOLANDA',  'mname'=>'GABRIEL',   'lname'=>'DELOS REYES','sex'=>'FEMALE'],
            ['fname'=>'ZOSIMO',   'mname'=>'HERNANDEZ', 'lname'=>'ESTRADA',    'sex'=>'MALE'],
            ['fname'=>'AURORA',   'mname'=>'ILAGAN',    'lname'=>'FERRER',     'sex'=>'FEMALE'],
        ];

        // =====================================================================
        // Claimant relationships (one per member)
        // =====================================================================

        $claimantRelationships = ['SPOUSE', 'HUSBAND', 'WIFE', 'PARENT', 'SIBLING'];

        $claimantPool = [
            ['fname'=>'ROSARIO',   'mname'=>'ENRIQUEZ',  'lname'=>'REYES'],
            ['fname'=>'BENITO',    'mname'=>'GARCIA',    'lname'=>'GARCIA'],
            ['fname'=>'TERESITA',  'mname'=>'TORRES',    'lname'=>'TORRES'],
            ['fname'=>'ERNESTO',   'mname'=>'FLORES',    'lname'=>'FLORES'],
            ['fname'=>'CATALINA',  'mname'=>'MEDINA',    'lname'=>'MEDINA'],
            ['fname'=>'ALFREDO',   'mname'=>'AQUINO',    'lname'=>'AQUINO'],
            ['fname'=>'PATRICIA',  'mname'=>'SANTIAGO',  'lname'=>'SANTIAGO'],
            ['fname'=>'MANUEL',    'mname'=>'CASTILLO',  'lname'=>'CASTILLO'],
            ['fname'=>'CORAZON',   'mname'=>'SORIANO',   'lname'=>'SORIANO'],
            ['fname'=>'ARTURO',    'mname'=>'PANGANIBAN','lname'=>'PANGANIBAN'],
            ['fname'=>'FLORENTINA','mname'=>'AGUILAR',   'lname'=>'AGUILAR'],
            ['fname'=>'SERGIO',    'mname'=>'MIRANDA',   'lname'=>'MIRANDA'],
            ['fname'=>'NATIVIDAD', 'mname'=>'NAVARRO',   'lname'=>'NAVARRO'],
            ['fname'=>'ROLANDO',   'mname'=>'HERRERA',   'lname'=>'HERRERA'],
            ['fname'=>'ESPERANZA', 'mname'=>'DELA TORRE','lname'=>'DELA TORRE'],
            ['fname'=>'DOMINADOR', 'mname'=>'CAMACHO',   'lname'=>'CAMACHO'],
            ['fname'=>'ADELAIDA',  'mname'=>'FUENTES',   'lname'=>'FUENTES'],
            ['fname'=>'VICTORINO', 'mname'=>'LOZANO',    'lname'=>'LOZANO'],
            ['fname'=>'CONCEPCION','mname'=>'CEDENO',    'lname'=>'CEDENO'],
            ['fname'=>'BARTOLOME', 'mname'=>'BLANCO',    'lname'=>'BLANCO'],
            ['fname'=>'FELICIDAD', 'mname'=>'CORONEL',   'lname'=>'CORONEL'],
            ['fname'=>'PORFIRIO',  'mname'=>'DELOS REYES','lname'=>'DELOS REYES'],
            ['fname'=>'MILAGROS',  'mname'=>'ESTRADA',   'lname'=>'ESTRADA'],
            ['fname'=>'CRISANTO',  'mname'=>'FERRER',    'lname'=>'FERRER'],
        ];

        // =====================================================================
        // Beneficiary first names pool (2 per member = 48 total)
        // =====================================================================

        $benFirstNames = [
            'MARK','JEROME','NEIL','PATRICK','RYAN','CARL','IVAN','RALPH',
            'CHAD','LANCE','REY','EDGAR','HANS','KEITH','LEON','TROY',
            'ANNE','CLAIRE','DANA','ELSA','FAITH','GRACE','HAZEL','IVY',
            'JANE','KATE','LARA','MARY','NINA','OLGA','PAULA','QUEEN',
            'RUTH','SHEILA','TINA','UNA','VERA','WENDY','XENA','YAEL',
            'ZOE','ABBY','BELLA','CHLOE','DIANA','EVA','FAYE','GINA',
        ];

        $benLastNames = [
            'REYES','GARCIA','TORRES','FLORES','MEDINA','AQUINO','SANTIAGO','CASTILLO',
            'SORIANO','PANGANIBAN','AGUILAR','MIRANDA','NAVARRO','HERRERA','DELA TORRE',
            'CAMACHO','FUENTES','LOZANO','CEDENO','BLANCO','CORONEL','DELOS REYES',
            'ESTRADA','FERRER',
        ];

        $benRelationships = ['SON', 'DAUGHTER', 'NIECE', 'NEPHEW', 'GRANDCHILD'];

        // =====================================================================
        // Counters for sequential numbers
        // =====================================================================

        $orCounter  = 21001;
        $appCounter = 2601;
        $benIdx     = 0;

        // =====================================================================
        // Loop: 3 MAS × 8 Members
        // =====================================================================

        foreach ($masUsers as $masIdx => $masUser) {

            $memberSlice   = array_slice($memberPool,   $masIdx * 8, 8);
            $claimantSlice = array_slice($claimantPool, $masIdx * 8, 8);

            foreach ($memberSlice as $mIdx => $mData) {

                $absoluteIdx = $masIdx * 8 + $mIdx;

                // --- Pick a random program ---
                $program   = $allPrograms->random();
                $amountMin = (float) ($program->amount_min ?? 500);

                // --- Member birthdate: 18-55 years old ---
                $memberBirthdate = Carbon::now()
                    ->subYears(rand(18, 55))
                    ->subDays(rand(0, 364))
                    ->format('Y-m-d');

                // Email uses lowercase convention
                $memberEmail = strtolower(
                    $mData['fname'] . '.' . str_replace(' ', '', $mData['lname'])
                ) . '@gmail.com';

                // --- Create Member ---
                $member = Member::create([
                    'fname'        => strtoupper(trim($mData['fname'])),
                    'mname'        => strtoupper(trim($mData['mname'])),
                    'lname'        => strtoupper(trim($mData['lname'])),
                    'sex'          => $mData['sex'],
                    'birthdate'    => $memberBirthdate,
                    'civil_status' => 'MARRIED',
                    'contact_num'  => '09' . rand(100000000, 999999999),
                    'email'        => $memberEmail,           // email stays lowercase
                    'address'      => 'BALIOK, DAVAO CITY',
                    'citizenship'  => 'FILIPINO',
                    'birthplace'   => 'DAVAO CITY',
                    'agent_id'     => $masUser->id,
                    'encoder_id'   => $encoder->id,
                    'branch_id'    => $baliokBranch->id,
                    'status'       => 'active',
                    'is_deleted'   => false,
                ]);

                // --- Create Claimant ---
                $cData           = $claimantSlice[$mIdx];
                $claimantBirthdate = Carbon::now()
                    ->subYears(rand(20, 55))
                    ->subDays(rand(0, 364))
                    ->format('Y-m-d');

                $claimant = Claimant::create([
                    'fname'       => strtoupper(trim($cData['fname'])),
                    'mname'       => strtoupper(trim($cData['mname'])),
                    'lname'       => strtoupper(trim($cData['lname'])),
                    'birthdate'   => $claimantBirthdate,
                    'sex'         => $mData['sex'] === 'MALE' ? 'FEMALE' : 'MALE',
                    'contact_num' => '09' . rand(100000000, 999999999),
                ]);

                // Update member with claimant_id
                $member->claimant_id = $claimant->id;
                $member->save();

                // --- Create 2 Beneficiaries ---
                for ($b = 0; $b < 2; $b++) {
                    $benBirthdate = Carbon::now()
                        ->subYears(rand(18, 55))
                        ->subDays(rand(0, 364))
                        ->format('Y-m-d');

                    $benFname = $benFirstNames[$benIdx % count($benFirstNames)];
                    $benLname = $benLastNames[$absoluteIdx % count($benLastNames)];
                    $benRel   = $benRelationships[array_rand($benRelationships)];
                    $benMname = strtoupper(trim($benLastNames[($absoluteIdx + $b + 1) % count($benLastNames)]));
                    $benSex   = in_array($benFname, [
                        'ANNE','CLAIRE','DANA','ELSA','FAITH','GRACE',
                        'HAZEL','IVY','JANE','KATE','LARA','MARY','NINA','OLGA','PAULA','QUEEN',
                        'RUTH','SHEILA','TINA','UNA','VERA','WENDY','XENA','YAEL','ZOE','ABBY',
                        'BELLA','CHLOE','DIANA','EVA','FAYE','GINA',
                    ]) ? 'FEMALE' : 'MALE';

                    $beneficiary = Beneficiary::create([
                        'fname'       => strtoupper(trim($benFname)),
                        'mname'       => $benMname,
                        'lname'       => strtoupper(trim($benLname)),
                        'birthdate'   => $benBirthdate,
                        'sex'         => $benSex,
                        'contact_num' => '09' . rand(100000000, 999999999),
                    ]);

                    // Attach to member via pivot
                    $member->beneficiaries()->syncWithoutDetaching([
                        $beneficiary->id => ['relationship' => strtoupper(trim($benRel))],
                    ]);

                    $benIdx++;
                }

                // --- Registration OR Date: random date in April 2025 ---
                $regDate = Carbon::create(2025, 4, rand(1, 28));

                // --- Create MembersProgram (New Sales) ---
                $memberProgram = MembersProgram::create([
                    'app_no'           => $appCounter++,
                    'encoder_id'       => $encoder->id,
                    'agent_id'         => $masUser->id,
                    'member_id'        => $member->id,
                    'program_id'       => $program->id,
                    'branch_id'        => $baliokBranch->id,
                    'claimant_id'      => $claimant->id,
                    'beneficiaries_ids'=> '',
                    'or_number'        => $orCounter,
                    'or_date'          => $regDate->copy()->addDays(rand(1, 2))->format('Y-m-d'),
                    'registration_fee' => 500,
                    'amount'           => $amountMin,
                    'transaction_type' => 'NEW SALES',
                    'status'           => 'active',
                    'is_deleted'       => false,
                    'is_remitted'      => true,
                    'created_at'       => $regDate,
                    'updated_at'       => $regDate,
                ]);

                // --- Registration Entry ---
                Entry::create([
                    'branch_id'        => $baliokBranch->id,
                    'encoder_id'       => $encoder->id,
                    'agent_id'         => $masUser->id,
                    'member_id'        => $member->id,
                    'or_number'        => $orCounter++,
                    'or_date'          => $regDate->copy()->addDays(rand(1, 2))->format('Y-m-d H:i:s'),
                    'amount'           => 500,
                    'number_of_payment'=> 1,
                    'program_id'       => $program->id,
                    'month_from'       => $regDate->format('Y-m'),
                    'month_to'         => $regDate->format('Y-m'),
                    'date_remitted'    => $regDate->format('Y-m-d H:i:s'),
                    'incentives'       => 0,
                    'is_reactivated'   => false,
                    'is_transferred'   => false,
                    'is_remitted'      => true,
                    'remarks'          => 'REGISTRATION',
                    'created_at'       => $regDate,
                    'updated_at'       => $regDate,
                ]);

                // --- Monthly Collection Entries (13 months starting May 2025) ---
                $termMax    = (int) ($program->term_max ?? 13);
                $paymentDay = rand(1, 28);

                for ($m = 0; $m < $termMax; $m++) {
                    $monthDate  = $regDate->copy()->addMonths($m + 1);
                    $yearMonth  = $monthDate->format('Y-m');

                    $paymentDate = Carbon::create(
                        $monthDate->year,
                        $monthDate->month,
                        $paymentDay
                    );

                    // OR Date is 1-2 days after payment date, capped to end of month
                    $orDayOffset = rand(1, 2);
                    $orDate      = $paymentDate->copy()->addDays($orDayOffset);
                    if ($orDate->month !== $paymentDate->month) {
                        $orDate = $paymentDate->copy()->endOfMonth()->startOfDay();
                    }

                    Entry::create([
                        'branch_id'        => $baliokBranch->id,
                        'encoder_id'       => $encoder->id,
                        'agent_id'         => $masUser->id,
                        'member_id'        => $member->id,
                        'or_number'        => $orCounter++,
                        'or_date'          => $orDate->format('Y-m-d H:i:s'),
                        'amount'           => $amountMin,
                        'number_of_payment'=> 1,
                        'program_id'       => $program->id,
                        'month_from'       => $yearMonth,
                        'month_to'         => $yearMonth,
                        'date_remitted'    => $paymentDate->format('Y-m-d H:i:s'),
                        'incentives'       => 0,
                        'is_reactivated'   => false,
                        'is_transferred'   => false,
                        'is_remitted'      => true,
                        'remarks'          => null,
                        'created_at'       => $paymentDate,
                        'updated_at'       => $paymentDate,
                    ]);
                }
            }
        }

        $this->command->info('Test data seeded successfully!');
        $this->command->info('3 MAS users, 24 Members, 24 Claimants, 48 Beneficiaries');
        $this->command->info('24 Registration entries + Monthly collection entries');
    }
}