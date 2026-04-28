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
        // PART 1: Excel Seeder
        // Seeds: Usertypes, Users (including all MAS agents), Branches, Programs, Matrix
        // Source of truth: storage/app/public/imports/DatabaseSeeder.xlsx
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

        // --- Usertypes ---
        $usertypes_arr = [];
        $i = 1;

        foreach (array_slice($usertypes, 1) as $row) {
            if ($row[1] != null && $row[1] != '') {
                Usertype::factory()->create([
                    'usertype' => strtoupper(trim($row[1])),
                ]);
                $usertypes_arr[$row[1]] = $i;
                $i++;
            }
        }

        // --- Users ---
        foreach (array_slice($users, 1) as $row) {
            if ($row[1] != '' && $row[1] != null) {
                User::factory()->create([
                    'username'    => $row[1],
                    'usertype'    => $usertypes_arr[$row[2]] ?? null,
                    'fname'       => strtoupper(trim($row[3])),
                    'mname'       => $row[4] ? strtoupper(trim($row[4])) : null,
                    'lname'       => strtoupper(trim($row[5])),
                    'ext'         => $row[6] ? strtoupper(trim($row[6])) : null,
                    'email'       => $row[7],
                    'contact_num' => $row[8],
                    'address'     => strtoupper(trim($row[9])),
                    'birthdate'   => is_numeric($row[10])
                        ? Date::excelToDateTimeObject($row[10])->format('Y-m-d')
                        : ($row[10] instanceof \DateTime ? $row[10]->format('Y-m-d') : null),
                    'password'    => Hash::make($row[11]),
                    'status'      => $row[12],
                    'branch_id'   => $row[13]
                        ? optional(DB::table('branches')->where('branch', strtoupper(trim($row[13])))->first())->id
                        : null,
                ]);
            }
        }

        // --- Branches ---
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

        // --- Programs ---
        foreach (array_slice($programs, 1) as $row) {
            if ($row[1] != '') {
                Program::factory()->create([
                    'code'                => strtoupper(trim($row[1])),
                    'description'         => strtoupper(trim($row[2])),
                    'beneficiaries_count' => $row[3],
                    'age_min'             => $row[4],
                    'age_max'             => $row[5],
                    'ben_age_min'         => $row[6],
                    'ben_age_max'         => $row[7],
                    'term_min'            => $row[8],
                    'term_max'            => $row[9],
                    'amount_min'          => $row[10],
                    'amount_max'          => $row[11],
                    'status'              => $row[12],
                ]);
            }
        }

        // =====================================================================
        // PART 2: Test Data Seeding (Members, Claimants, Beneficiaries, Entries)
        // Seeds synthetic data under the BALIOK branch for testing purposes.
        // MAS users are no longer created here — they are seeded from the Excel
        // file above (Users sheet, usertype = MARKETTING ACCOUNT STAFF).
        // =====================================================================

        // --- Resolve BALIOK branch ---
        $baliokBranch = DB::table('branches')
            ->where('branch', 'BALIOK')
            ->first();

        if (!$baliokBranch) {
            $this->command->warn('BALIOK branch not found. Make sure it exists in DatabaseSeeder.xlsx.');
            return;
        }

        // --- Resolve all programs ---
        $allPrograms = DB::table('programs')->get();

        if ($allPrograms->isEmpty()) {
            $this->command->warn('No programs found. Make sure programs are seeded.');
            return;
        }

        // --- Resolve an Encoder (usertype = 2 = ENTRY CLERK) ---
        $encoder = DB::table('users')->where('usertype', 2)->first();

        if (!$encoder) {
            $this->command->warn('No encoder (usertype=2) found. Please seed users first.');
            return;
        }

        // --- Resolve the 3 MAS users for test data from the seeded users table ---
        // These users are now seeded from the Excel file (JOSE DELA CRUZ,
        // MARIA GONZALES, RICARDO VILLANUEVA — all under BALIOK branch).
        $masUserRecords = [
            ['fname' => 'JOSE',    'lname' => 'DELA CRUZ'],
            ['fname' => 'MARIA',   'lname' => 'GONZALES'],
            ['fname' => 'RICARDO', 'lname' => 'VILLANUEVA'],
        ];

        $masUsers = [];

        foreach ($masUserRecords as $masRef) {
            $user = DB::table('users')
                ->whereRaw('UPPER(fname) = ?', [$masRef['fname']])
                ->whereRaw('UPPER(lname) = ?', [$masRef['lname']])
                ->first();

            if (!$user) {
                $this->command->warn("MAS user not found: {$masRef['fname']} {$masRef['lname']}. Make sure the Excel file is up to date.");
                return;
            }

            $masUsers[] = $user;
        }

        // =====================================================================
        // Member name pool (24 unique members, 8 per MAS)
        // =====================================================================

        $memberPool = [
            // MAS 1 members
            ['fname' => 'ANTONIO',   'mname' => 'LUNA',       'lname' => 'REYES',       'sex' => 'MALE'],
            ['fname' => 'CAROLINA',  'mname' => 'MENDOZA',    'lname' => 'GARCIA',      'sex' => 'FEMALE'],
            ['fname' => 'FERNANDO',  'mname' => 'RAMOS',      'lname' => 'TORRES',      'sex' => 'MALE'],
            ['fname' => 'GLORIA',    'mname' => 'SANTOS',     'lname' => 'FLORES',      'sex' => 'FEMALE'],
            ['fname' => 'HERMINIO',  'mname' => 'CRUZ',       'lname' => 'MEDINA',      'sex' => 'MALE'],
            ['fname' => 'ISABELITA', 'mname' => 'BAUTISTA',   'lname' => 'AQUINO',      'sex' => 'FEMALE'],
            ['fname' => 'JUAN',      'mname' => 'DELA PENA',  'lname' => 'SANTIAGO',    'sex' => 'MALE'],
            ['fname' => 'KRISTINA',  'mname' => 'VALDEZ',     'lname' => 'CASTILLO',    'sex' => 'FEMALE'],
            // MAS 2 members
            ['fname' => 'LORENZO',   'mname' => 'FERNANDEZ',  'lname' => 'SORIANO',     'sex' => 'MALE'],
            ['fname' => 'MELINDA',   'mname' => 'DIAZ',       'lname' => 'PANGANIBAN',  'sex' => 'FEMALE'],
            ['fname' => 'NESTOR',    'mname' => 'PASCUAL',    'lname' => 'AGUILAR',     'sex' => 'MALE'],
            ['fname' => 'OFELIA',    'mname' => 'GUERRERO',   'lname' => 'MIRANDA',     'sex' => 'FEMALE'],
            ['fname' => 'PEDRO',     'mname' => 'SALAZAR',    'lname' => 'NAVARRO',     'sex' => 'MALE'],
            ['fname' => 'QUIRINA',   'mname' => 'MOLINA',     'lname' => 'HERRERA',     'sex' => 'FEMALE'],
            ['fname' => 'RODRIGO',   'mname' => 'ESPIRITU',   'lname' => 'DELA TORRE',  'sex' => 'MALE'],
            ['fname' => 'SOLEDAD',   'mname' => 'IGNACIO',    'lname' => 'CAMACHO',     'sex' => 'FEMALE'],
            // MAS 3 members
            ['fname' => 'TIMOTEO',   'mname' => 'ABAD',       'lname' => 'FUENTES',     'sex' => 'MALE'],
            ['fname' => 'URSULA',    'mname' => 'CONCEPCION', 'lname' => 'LOZANO',      'sex' => 'FEMALE'],
            ['fname' => 'VIRGILIO',  'mname' => 'DOMINGO',    'lname' => 'CEDENO',      'sex' => 'MALE'],
            ['fname' => 'WILHELMINA','mname' => 'ESPINOSA',   'lname' => 'BLANCO',      'sex' => 'FEMALE'],
            ['fname' => 'XAVIER',    'mname' => 'FRANCISCO',  'lname' => 'CORONEL',     'sex' => 'MALE'],
            ['fname' => 'YOLANDA',   'mname' => 'GABRIEL',    'lname' => 'DELOS REYES', 'sex' => 'FEMALE'],
            ['fname' => 'ZOSIMO',    'mname' => 'HERNANDEZ',  'lname' => 'ESTRADA',     'sex' => 'MALE'],
            ['fname' => 'AURORA',    'mname' => 'ILAGAN',     'lname' => 'FERRER',      'sex' => 'FEMALE'],
        ];

        // =====================================================================
        // Claimant pool (one per member, 24 total)
        // =====================================================================

        $claimantRelationships = ['SPOUSE', 'HUSBAND', 'WIFE', 'PARENT', 'SIBLING'];

        $claimantPool = [
            ['fname' => 'ROSARIO',    'mname' => 'ENRIQUEZ',   'lname' => 'REYES'],
            ['fname' => 'BENITO',     'mname' => 'GARCIA',     'lname' => 'GARCIA'],
            ['fname' => 'TERESITA',   'mname' => 'TORRES',     'lname' => 'TORRES'],
            ['fname' => 'ERNESTO',    'mname' => 'FLORES',     'lname' => 'FLORES'],
            ['fname' => 'CATALINA',   'mname' => 'MEDINA',     'lname' => 'MEDINA'],
            ['fname' => 'ALFREDO',    'mname' => 'AQUINO',     'lname' => 'AQUINO'],
            ['fname' => 'PATRICIA',   'mname' => 'SANTIAGO',   'lname' => 'SANTIAGO'],
            ['fname' => 'MANUEL',     'mname' => 'CASTILLO',   'lname' => 'CASTILLO'],
            ['fname' => 'CORAZON',    'mname' => 'SORIANO',    'lname' => 'SORIANO'],
            ['fname' => 'ARTURO',     'mname' => 'PANGANIBAN', 'lname' => 'PANGANIBAN'],
            ['fname' => 'FLORENTINA', 'mname' => 'AGUILAR',    'lname' => 'AGUILAR'],
            ['fname' => 'SERGIO',     'mname' => 'MIRANDA',    'lname' => 'MIRANDA'],
            ['fname' => 'NATIVIDAD',  'mname' => 'NAVARRO',    'lname' => 'NAVARRO'],
            ['fname' => 'ROLANDO',    'mname' => 'HERRERA',    'lname' => 'HERRERA'],
            ['fname' => 'ESPERANZA',  'mname' => 'DELA TORRE', 'lname' => 'DELA TORRE'],
            ['fname' => 'DOMINADOR',  'mname' => 'CAMACHO',    'lname' => 'CAMACHO'],
            ['fname' => 'ADELAIDA',   'mname' => 'FUENTES',    'lname' => 'FUENTES'],
            ['fname' => 'VICTORINO',  'mname' => 'LOZANO',     'lname' => 'LOZANO'],
            ['fname' => 'CONCEPCION', 'mname' => 'CEDENO',     'lname' => 'CEDENO'],
            ['fname' => 'BARTOLOME',  'mname' => 'BLANCO',     'lname' => 'BLANCO'],
            ['fname' => 'FELICIDAD',  'mname' => 'CORONEL',    'lname' => 'CORONEL'],
            ['fname' => 'PORFIRIO',   'mname' => 'DELOS REYES','lname' => 'DELOS REYES'],
            ['fname' => 'MILAGROS',   'mname' => 'ESTRADA',    'lname' => 'ESTRADA'],
            ['fname' => 'CRISANTO',   'mname' => 'FERRER',     'lname' => 'FERRER'],
        ];

        // =====================================================================
        // Beneficiary name pools (2 per member = 48 total)
        // =====================================================================

        $benFirstNames = [
            'MARK',   'JEROME', 'NEIL',   'PATRICK', 'RYAN',  'CARL',  'IVAN',  'RALPH',
            'CHAD',   'LANCE',  'REY',    'EDGAR',   'HANS',  'KEITH', 'LEON',  'TROY',
            'ANNE',   'CLAIRE', 'DANA',   'ELSA',    'FAITH', 'GRACE', 'HAZEL', 'IVY',
            'JANE',   'KATE',   'LARA',   'MARY',    'NINA',  'OLGA',  'PAULA', 'QUEEN',
            'RUTH',   'SHEILA', 'TINA',   'UNA',     'VERA',  'WENDY', 'XENA',  'YAEL',
            'ZOE',    'ABBY',   'BELLA',  'CHLOE',   'DIANA', 'EVA',   'FAYE',  'GINA',
        ];

        $benLastNames = [
            'REYES',      'GARCIA',    'TORRES',     'FLORES',     'MEDINA',   'AQUINO',
            'SANTIAGO',   'CASTILLO',  'SORIANO',    'PANGANIBAN', 'AGUILAR',  'MIRANDA',
            'NAVARRO',    'HERRERA',   'DELA TORRE', 'CAMACHO',    'FUENTES',  'LOZANO',
            'CEDENO',     'BLANCO',    'CORONEL',    'DELOS REYES','ESTRADA',  'FERRER',
        ];

        $benRelationships = ['SON', 'DAUGHTER', 'NIECE', 'NEPHEW', 'GRANDCHILD'];

        // =====================================================================
        // Counters for sequential OR and App numbers
        // =====================================================================

        $orCounter  = 21001;
        $appCounter = 2601;
        $benIdx     = 0;

        // =====================================================================
        // Loop: 3 MAS × 8 Members each
        // =====================================================================

        foreach ($masUsers as $masIdx => $masUser) {

            $memberSlice   = array_slice($memberPool,   $masIdx * 8, 8);
            $claimantSlice = array_slice($claimantPool, $masIdx * 8, 8);

            foreach ($memberSlice as $mIdx => $mData) {

                // --- Pick a random program ---
                $program   = $allPrograms->random();
                $amountMin = (float) ($program->amount_min ?? 500);

                // --- Member birthdate: 18–55 years old ---
                $memberBirthdate = Carbon::now()
                    ->subYears(rand(18, 55))
                    ->subDays(rand(0, 364))
                    ->format('Y-m-d');

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
                    'email'        => $memberEmail,
                    'address'      => 'BALIOK, DAVAO CITY',
                    'citizenship'  => 'FILIPINO',
                    'birthplace'   => 'DAVAO CITY',
                    'agent_id'     => $masUser->id,
                    'encoder_id'   => $encoder->id,
                    'branch_id'    => $baliokBranch->id,
                    'status'       => 'active',
                ]);

                // --- Create Claimant ---
                $cData             = $claimantSlice[$mIdx];
                $claimantBirthdate = Carbon::now()
                    ->subYears(rand(20, 55))
                    ->subDays(rand(0, 364))
                    ->format('Y-m-d');

                $claimant = Claimant::create([
                    'fname'        => strtoupper(trim($cData['fname'])),
                    'mname'        => strtoupper(trim($cData['mname'])),
                    'lname'        => strtoupper(trim($cData['lname'])),
                    'birthdate'    => $claimantBirthdate,
                    'sex'          => $mData['sex'] === 'MALE' ? 'FEMALE' : 'MALE',
                    'contact_num'  => '09' . rand(100000000, 999999999),
                    'relationship' => $claimantRelationships[array_rand($claimantRelationships)],
                ]);

                $member->update(['claimant_id' => $claimant->id]);

                // --- Create 2 Beneficiaries ---
                for ($b = 0; $b < 2; $b++) {
                    $benBirthdate = Carbon::now()
                        ->subYears(rand(18, 50))
                        ->subDays(rand(0, 364))
                        ->format('Y-m-d');

                    $beneficiary = Beneficiary::create([
                        'fname'        => $benFirstNames[$benIdx % count($benFirstNames)],
                        'mname'        => null,
                        'lname'        => $benLastNames[$benIdx % count($benLastNames)],
                        'birthdate'    => $benBirthdate,
                        'sex'          => ($benIdx % 2 === 0) ? 'MALE' : 'FEMALE',
                        'contact_num'  => '09' . rand(100000000, 999999999),
                        'relationship' => $benRelationships[array_rand($benRelationships)],
                    ]);

                    $member->beneficiaries()->attach($beneficiary->id, [
                        'relationship' => $beneficiary->relationship,
                    ]);

                    $benIdx++;
                }

                // --- Create MembersProgram (New Sales record) ---
                $regDate = Carbon::create(2025, rand(1, 4), rand(1, 28));

                $membersProgram = MembersProgram::create([
                    'member_id'        => $member->id,
                    'program_id'       => $program->id,
                    'branch_id'        => $baliokBranch->id,
                    'app_no'           => $appCounter++,
                    'or_number'        => $orCounter,
                    'or_date'          => $regDate->copy()->addDays(rand(1, 2))->format('Y-m-d H:i:s'),
                    'amount'           => 500,
                    'encoder_id'       => $encoder->id,
                    'agent_id'         => $masUser->id,
                    'registration_fee' => 500,
                    'status'           => 'active',
                ]);

                // --- Registration Entry ---
                Entry::create([
                    'branch_id'         => $baliokBranch->id,
                    'encoder_id'        => $encoder->id,
                    'agent_id'          => $masUser->id,
                    'member_id'         => $member->id,
                    'or_number'         => $orCounter++,
                    'or_date'           => $regDate->copy()->addDays(rand(1, 2))->format('Y-m-d H:i:s'),
                    'amount'            => 500,
                    'number_of_payment' => 1,
                    'program_id'        => $program->id,
                    'month_from'        => $regDate->format('Y-m'),
                    'month_to'          => $regDate->format('Y-m'),
                    'date_remitted'     => $regDate->format('Y-m-d H:i:s'),
                    'incentives'        => 0,
                    'is_reactivated'    => false,
                    'is_transferred'    => false,
                    'is_remitted'       => true,
                    'remarks'           => 'REGISTRATION',
                    'created_at'        => $regDate,
                    'updated_at'        => $regDate,
                ]);

                // --- Monthly Collection Entries (13 months starting after registration) ---
                $termMax    = 13;
                $paymentDay = rand(1, 28);

                for ($m = 0; $m < $termMax; $m++) {
                    $monthDate   = $regDate->copy()->addMonths($m + 1);
                    $yearMonth   = $monthDate->format('Y-m');
                    $paymentDate = Carbon::create(
                        $monthDate->year,
                        $monthDate->month,
                        $paymentDay
                    );

                    // OR date is 1–2 days after payment, capped to end of month
                    $orDayOffset = rand(1, 2);
                    $orDate      = $paymentDate->copy()->addDays($orDayOffset);
                    if ($orDate->month !== $paymentDate->month) {
                        $orDate = $paymentDate->copy()->endOfMonth()->startOfDay();
                    }

                    Entry::create([
                        'branch_id'         => $baliokBranch->id,
                        'encoder_id'        => $encoder->id,
                        'agent_id'          => $masUser->id,
                        'member_id'         => $member->id,
                        'or_number'         => $orCounter++,
                        'or_date'           => $orDate->format('Y-m-d H:i:s'),
                        'amount'            => $amountMin,
                        'number_of_payment' => 1,
                        'program_id'        => $program->id,
                        'month_from'        => $yearMonth,
                        'month_to'          => $yearMonth,
                        'date_remitted'     => $paymentDate->format('Y-m-d H:i:s'),
                        'incentives'        => 0,
                        'is_reactivated'    => false,
                        'is_transferred'    => false,
                        'is_remitted'       => true,
                        'remarks'           => null,
                        'created_at'        => $paymentDate,
                        'updated_at'        => $paymentDate,
                    ]);
                }
            }
        }

        $this->command->info('Seeding complete!');
        $this->command->info('Part 1: Usertypes, Users (incl. all MAS agents), Branches, Programs, Matrix seeded from Excel.');
        $this->command->info('Part 2: 24 Members, 24 Claimants, 48 Beneficiaries, 24 MembersProgram, registration + monthly entries seeded.');
    }
}
