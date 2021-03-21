<?php /** @noinspection PhpMissingFieldTypeInspection */
/** @noinspection AccessModifierPresentedInspection */
/** @noinspection ReturnTypeCanBeDeclaredInspection */

/** @noinspection PhpMissingReturnTypeInspection */

namespace Mshm\User\Models;

use Eloquent;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Mshm\Course\Models\Course;
use Mshm\Course\Models\Lesson;
use Mshm\Course\Models\Season;
use Mshm\Media\Models\Media;
use Mshm\RolePermissions\Models\Role;
use Mshm\User\Notifications\ResetPasswordRequestNotification;
use Mshm\User\Notifications\VerifyMailNotification;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\User
 *
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @mixin Eloquent
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $mobile
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property mixed username
 * @property mixed image
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static permission(string $string)
 * @method static findOrCreate($user)
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use HasRoles;
    use HasFactory;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_BAN = 'ban';
    public static $statuses = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
        self::STATUS_BAN,
    ];

    public static $defaultUsers = [
        [
            'email' => 'admin@admin.com',
            'password' => '@M123m',
            'name' => 'Admin',
            'role' => Role::ROLE_SUPER_ADMIN,
        ],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'mobile',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyMailNotification());
    }

    public function sendResetPasswordRequestNotification()
    {
        $this->notify(new ResetPasswordRequestNotification());
    }

    public function image()
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    public function purchases()
    {
        return $this->belongsToMany(Course::class, 'course_user',
            'user_id', 'course_id');
    }

    public function seasons()
    {
        return $this->hasMany(Season::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function profilePath()
    {
        return $this->username ? route('viewProfile', $this->username)
            : route('viewProfile', 'username');
    }

    public function getThumbAttribute()
    {
        if ($this->image) {
            return '/storage/' . $this->image->files[300];
        }
        return '/panel/img/profile.jpg';
    }

    public function studentsCount()
    {
        return DB::table("courses")
            ->select("course_id")
            ->where("teacher_id", $this->id)
            ->join("course_user", "courses.id", "=", "course_user.course_id")
            ->count();
    }

}
