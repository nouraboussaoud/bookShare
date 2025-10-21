# 🎯 Quick Manual Testing - Polls & Notifications

## ✅ Status Check

Migrations completed successfully:
- ✅ `2025_10_21_120000_create_polls_table`
- ✅ `2025_10_21_120001_create_poll_options_table`
- ✅ `2025_10_21_120002_create_poll_votes_table`

---

## 🚀 Start Testing Now

### Step 1: Start Your Server

```bash
php artisan serve
```

**Expected Output:**
```
Laravel development server started on [http://127.0.0.1:8000]
```

---

### Step 2: Open in Browser

Go to: `http://localhost:8000`

---

### Step 3: Login or Create Account

1. Create account or login
2. Navigate to **Dashboard**

---

### Step 4: Create a Reading Group (if needed)

1. Click **"+ Créer un groupe"** or find existing group
2. Select a group to enter

---

### Step 5: Create an Event

**Navigation:**
- Dashboard → Select Reading Group → **Events** tab → **Create Event**

**Fill in:**
- Title: `"Book Discussion Oct 25"`
- Date: Pick a future date
- Time: `19:00`
- Location: `"Online"` or specific place
- Duration: `120` minutes

**Click:** `"Create Event"` ✅

---

### Step 6: See Polls Widget on Event Page

After creating event, you should see:

```
📊 Sondages actifs

+ Créer un sondage

[There are no polls yet - create one!]
```

✅ **If you see this, polls are integrated!**

---

### Step 7: Create First Poll

**Click:** `"+ Créer un sondage"`

**Choose Type: "Oui/Non"**

**Fill Form:**
- Title: `"Should we discuss this book first?"`
- Description: (leave empty)
- Type: Select `"Oui/Non"`

**Click:** `"Créer le sondage"` ✅

---

### Step 8: See Poll in Widget

Back on event page, you should now see:

```
📊 Sondages actifs

🟢 Sondage actif
0 vote(s)
Oui/Non

[Voting card with:]
"Should we discuss this book first?"
Oui [50%] | Non [50%]

"Voter ou voir les résultats"
```

✅ **Poll appears on event page!**

---

### Step 9: Vote on Poll

**Click:** `"Voter ou voir les résultats"`

**On poll page, you should see:**
- Poll title and description
- Status: `🟢 Sondage actif`
- Voting form with `Oui` / `Non` options
- Results section showing percentages

**Vote:**
1. Select `"Oui"`
2. Click `"Voter"`

✅ **You see results update!**

---

### Step 10: Test Multiple Choice Poll

**Back on event page**, create another poll:

**Fill:**
- Title: `"Which book next?"`
- Type: `"Choix multiples"`
- Options:
  - `"The Great Gatsby"`
  - `"Pride and Prejudice"`
  - `"1984"`
- Click `"+ Ajouter une option"` to add more

**Create** ✅

---

### Step 11: Test Rating Poll

Create another poll:

**Fill:**
- Title: `"Rate this event (1-5)"`
- Type: `"Évaluation"`

**Create** ✅

**Vote:**
- Click poll
- Click a number (1-5)
- Click `"Voter"`

✅ **See average rating displayed!**

---

## 🔔 Test Notifications

### Access Test Page

Go to: `http://localhost:8000/test/notifications-polls`

**You should see:**
- Event count
- Poll count
- Vote count
- Your recent notifications list

---

### Test 24-Hour Reminder

**On test page:**
1. Select an event from dropdown
2. Select `"24 Hours Before"`
3. Click `"Send Reminder"`

**Expected:**
```
✅ 24-hour reminder sent to X attendees
```

---

### Check Notifications

Go to: `http://localhost:8000/notifications`

**You should see a new notification:**
```
Rappel: Événement dans 24 heures

N'oubliez pas ! L'événement "Book Discussion" commence demain à 19:00
```

✅ **Notifications working!**

---

## 🧪 Export Polls Results

### Create Some Votes

1. Go to an event with a poll
2. Click on poll
3. Vote multiple times (use incognito window or different account)

---

### Export Results

**On poll page:**
1. Click `"📥 Exporter CSV"` button
2. File downloads to your computer

**Open CSV file** (Excel/Numbers):
- Poll metadata
- All options with vote counts
- Percentages
- Total votes
- Export date

✅ **CSV export working!**

---

## 📱 Test Different Devices

### Different Browsers
- [ ] Chrome
- [ ] Firefox
- [ ] Safari
- [ ] Edge

### Different Users
- [ ] Create 2nd account
- [ ] Login as different user
- [ ] Vote on same poll
- [ ] Results update

---

## 🎯 Verification Checklist

### Polls
- [ ] Polls appear on event page
- [ ] Can create yes/no poll
- [ ] Can create multiple choice poll
- [ ] Can create rating poll
- [ ] Can vote on poll
- [ ] Results display with percentages
- [ ] Can vote again to change vote
- [ ] Can export as CSV
- [ ] Can close poll (if organizer)
- [ ] Can delete poll (if organizer)

### Notifications
- [ ] Can view notifications
- [ ] Can manually send reminders
- [ ] Reminders appear in notification list
- [ ] Notification count updates
- [ ] Can mark notifications as read

### Integration
- [ ] Event page loads without errors
- [ ] Polls widget included on event page
- [ ] Event relationships working
- [ ] Observer automatically registered
- [ ] No console errors in browser

---

## 🐛 If Something Doesn't Work

### Polls Don't Show on Event Page

1. **Check database:**
```bash
php artisan tinker
>>> DB::table('polls')->count()
```

2. **Check event date/time:**
```bash
>>> $event = \App\Models\GroupEvent::first();
>>> $event->event_date;
>>> $event->event_time;
```

3. **Check widget included:**
```blade
<!-- Should be in groups/events/show.blade.php -->
@include('polls.event-polls-widget', ['event' => $event])
```

### Notifications Don't Send

1. **Start queue worker:**
```bash
# In another terminal:
php artisan queue:work
```

2. **Or use sync queue:**
```env
# In .env:
QUEUE_CONNECTION=sync
```

3. **Check notifications created:**
```bash
php artisan tinker
>>> \App\Models\Notification::latest()->limit(5)->get();
```

### Can't Vote

1. Clear browser cache (Ctrl+Shift+Delete)
2. Try incognito window
3. Check poll is not closed: `$poll->isActive()`

---

## 📊 Useful Commands

### View Database
```bash
php artisan tinker

# Count polls
>>> \App\Models\Poll::count()

# Count votes
>>> \App\Models\PollVote::count()

# Get active polls
>>> \App\Models\Poll::where('is_active', true)->get()

# Get event with polls
>>> $event = \App\Models\GroupEvent::first();
>>> $event->polls;

# Get poll results
>>> $poll = \App\Models\Poll::first();
>>> $poll->getResults();
```

### Test Notifications
```bash
php artisan tinker

# Send notification
>>> $event = \App\Models\GroupEvent::first();
>>> app(\App\Services\NotificationService::class)->notifyEventReminder24h($event);

# Check created
>>> \App\Models\Notification::latest()->first();
```

---

## ✨ Everything Works!

If you've completed all steps above and things are working, congratulations! 🎉

Your event polls and notifications system is:
- ✅ Integrated
- ✅ Tested
- ✅ Working
- ✅ Ready for production

---

## 🚀 Next Steps

1. **Add more events and test scenarios**
2. **Get feedback from users**
3. **Monitor performance**
4. **Optional: Add WebSocket for real-time updates**
5. **Optional: Add poll templates**
6. **Optional: Add anonymous voting**

---

**Happy polling!** 🗳️
