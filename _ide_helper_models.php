<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $Member_id
 * @property int $tutor_id
 * @property int $subject_id
 * @property \Illuminate\Support\Carbon $tanggal
 * @property string $jam_mulai
 * @property string $jam_selesai
 * @property int $harga
 * @property string $status
 * @property string|null $catatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $Member
 * @property-read \App\Models\Review|null $review
 * @property-read \App\Models\Subject $subject
 * @property-read \App\Models\User $tutor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereJamMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereJamSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereTutorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereUpdatedAt($value)
 */
	class Booking extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $Member_id
 * @property int $tutor_id
 * @property numeric $skor_lokasi
 * @property numeric $skor_mapel
 * @property numeric $skor_harga
 * @property numeric $skor_jadwal
 * @property numeric $skor_rating
 * @property numeric $skor_total
 * @property array<array-key, mixed>|null $kriteria_input
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $Member
 * @property-read \App\Models\User $tutor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchingLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchingLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchingLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchingLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchingLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchingLog whereKriteriaInput($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchingLog whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchingLog whereSkorHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchingLog whereSkorJadwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchingLog whereSkorLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchingLog whereSkorMapel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchingLog whereSkorRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchingLog whereSkorTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchingLog whereTutorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MatchingLog whereUpdatedAt($value)
 */
	class MatchingLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $booking_id
 * @property int $Member_id
 * @property int $tutor_id
 * @property int $rating
 * @property string|null $komentar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Booking $booking
 * @property-read \App\Models\User $Member
 * @property-read \App\Models\User $tutor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereBookingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereKomentar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereTutorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereUpdatedAt($value)
 */
	class Review extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $tutor_profile_id
 * @property string $hari
 * @property string $jam_mulai
 * @property string $jam_selesai
 * @property bool $is_available
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TutorProfile $tutorProfile
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereHari($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereIsAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereJamMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereJamSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereTutorProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereUpdatedAt($value)
 */
	class Schedule extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama_mapel
 * @property string|null $kategori
 * @property string|null $icon
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookings
 * @property-read int|null $bookings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TutorProfile> $tutorProfiles
 * @property-read int|null $tutor_profiles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject whereNamaMapel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subject whereUpdatedAt($value)
 */
	class Subject extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string|null $bio
 * @property int $harga_min
 * @property int $harga_max
 * @property string|null $pendidikan
 * @property string|null $universitas
 * @property string|null $dokumen_ktp
 * @property string|null $dokumen_ijazah
 * @property numeric $rating_rata
 * @property int $total_review
 * @property int $total_Member
 * @property string $status_verifikasi
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Schedule> $schedules
 * @property-read int|null $schedules_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subject> $subjects
 * @property-read int|null $subjects_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorProfile whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorProfile whereDokumenIjazah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorProfile whereDokumenKtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorProfile whereHargaMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorProfile whereHargaMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorProfile whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorProfile wherePendidikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorProfile whereRatingRata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorProfile whereStatusVerifikasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorProfile whereTotalMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorProfile whereTotalReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorProfile whereUniversitas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorProfile whereUserId($value)
 */
	class TutorProfile extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $tutor_profile_id
 * @property int $subject_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorSubject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorSubject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorSubject query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorSubject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorSubject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorSubject whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorSubject whereTutorProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TutorSubject whereUpdatedAt($value)
 */
	class TutorSubject extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property string|null $no_hp
 * @property string|null $foto_profil
 * @property string|null $alamat
 * @property string|null $kota
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookingsAsMember
 * @property-read int|null $bookings_as_Member_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookingsAsTutor
 * @property-read int|null $bookings_as_tutor_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MatchingLog> $matchingLogs
 * @property-read int|null $matching_logs_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviewsAsMember
 * @property-read int|null $reviews_as_Member_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviewsAsTutor
 * @property-read int|null $reviews_as_tutor_count
 * @property-read \App\Models\TutorProfile|null $tutorProfile
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFotoProfil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereKota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

