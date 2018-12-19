<?php

namespace Third\Doorman;

use Carbon\Carbon;
use Third\Doorman\Exceptions\DuplicateException;
use Third\Doorman\Models\Invite;
use Illuminate\Support\Str;

class Generator
{
    protected $amount = 1;
    protected $uses = 9999;
    protected $email = null;
    protected $owner_user_id = null;
    protected $expiry;

    /**
     * @var \Third\Doorman\DoormanManager
     */
    protected $manager;

    public function __construct(DoormanManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param int $amount
     *
     * @return $this
     */
    public function times(int $amount = 1)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @param int $amount
     *
     * @return $this
     */
    public function uses(int $amount = 1)
    {
        $this->uses = $amount;

        return $this;
    }

    /**
     * @param string $email
     *
     * @return $this
     * @throws \Third\Doorman\Exceptions\DuplicateException
     */
    public function for(string $email)
    {
        if (Invite::where('for', strtolower($email))->first()) {
            throw new DuplicateException('You cannot create more than 1 invite code for an email');
        }

        $this->email = $email;

        return $this;
    }

    /**
     *
     * owner
     *
     * @param int $user_id
     * @return $this
     * @throws DuplicateException
     */
    public function owner (int $user_id)
    {
        if (Invite::where('owner_user_id', $user_id)->first()) {
            throw new DuplicateException('You cannot create more than 1 invite code for an user_id');
        }

        $this->owner_user_id = $user_id;

        return $this;
    }

    /**
     * @param \Carbon\Carbon $date
     *
     * @return $this
     */
    public function expiresOn(Carbon $date)
    {
        $this->expiry = $date;

        return $this;
    }

    /**
     * @param int $days
     *
     * @return $this
     */
    public function expiresIn($days = 14)
    {
        $this->expiry = Carbon::now(config('app.timezone'))->addDays($days)->endOfDay();

        return $this;
    }

    /**
     * @return \Third\Doorman\Models\Invite
     */
    protected function build(): Invite
    {
        $invite = new Invite;
        $invite->code = Str::upper($this->manager->code());
        $invite->for = $this->email;
        $invite->owner_user_id = $this->owner_user_id;
        $invite->max = $this->uses;
        $invite->valid_until = $this->expiry;

        return $invite;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function make()
    {
        $invites = collect();

        if (!is_null($this->email) && $this->amount > 1) {
            throw new DuplicateException('You cannot create more than 1 invite code for an email');
        }

        for ($i = 0; $i < $this->amount; $i++) {
            $invite = $this->build();

            $invites->push($invite);

            $invite->save();
        }

        return $invites;
    }
}
