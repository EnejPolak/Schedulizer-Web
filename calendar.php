<?php
// calendar.php
session_start();
include 'toolbar.php';
?>

<!DOCTYPE html>
<html lang="sl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>

    <style>
        /* Glavni content */
        #main-content {
            margin-left: 260px;
            padding: 20px;
            font-family: 'Mukta', sans-serif;
            background: linear-gradient(135deg, #3A82F7 0%, #00C2FF 100%);
            overflow: hidden;
            position: relative;
            min-height: 100vh;
        }


        /* Calendar okvir */
        .calendar-wrapper {
            background: #EDEFEF;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            margin: auto;
            position: relative;
        }

        .week-header {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
            font-size: 18px;
            flex-wrap: wrap;
        }

        .arrow-btn,
        .today-btn {
            font-size: 16px;
            padding: 6px 12px;
            background-color: #00C2FF;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .arrow-btn:hover,
        .today-btn:hover {
            background-color: #009ad6;
        }

        .calendar-table {
            display: grid;
            grid-template-columns: 100px repeat(7, 1fr);
            gap: 10px;
            align-items: center;
        }

        .header {
            font-weight: bold;
            text-align: center;
            color: #333;
            font-size: 14px;
            padding: 6px;
            border-radius: 6px;
        }

        .today {
            background: linear-gradient(135deg, #00C2FF, #3A82F7);
            color: white;
            font-weight: bold;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transform: scale(1.05);
            transition: all 0.3s ease;
        }

        .time-label {
            font-weight: bold;
            padding-left: 10px;
            font-size: 14px;
        }

        .calendar-cell {
            height: 60px;
            background: #f0f0f0;
            border: 2px solid black;
            border-radius: 8px;
            cursor: pointer;
        }

        .radio-options {
            margin-top: 30px;
            text-align: center;
            font-size: 18px;
            display: flex;
            justify-content: center;
            gap: 40px;
        }

        .radio-options input[type="radio"] {
            margin-right: 8px;
            transform: scale(1.3);
        }

        input[type="radio"][value="can"]:checked {
            accent-color: green;
        }

        input[type="radio"][value="cant"]:checked {
            accent-color: red;
        }

        input[type="radio"][value="swap"]:checked {
            accent-color: orange;
        }

        .send-button-wrapper {
            text-align: center;
            margin-top: 30px;
        }

        .send-schedule-btn {
            position: relative;
            overflow: hidden;
            padding: 12px 30px;
            font-size: 18px;
            border: none;
            border-radius: 12px;
            color: white;
            background: linear-gradient(45deg, #00C2FF, #3A82F7);
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
            animation: pulseBtn 2.5s infinite;
        }

        .send-schedule-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(0, 194, 255, 0.7);
        }

        /* Ripple effect */
        .send-schedule-btn::after {
            content: '';
            position: absolute;
            left: 50%;
            top: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 100%;
            transform: translate(-50%, -50%);
            pointer-events: none;
            transition: width 0.4s ease, height 0.4s ease;
        }

        .send-schedule-btn:active::after {
            width: 200px;
            height: 200px;
            transition: 0s;
        }




        /* Popup za send */
        .popup-message {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #e0ffe0;
            color: #1a6600;
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            font-size: 20px;
            z-index: 20;
            display: none;
            text-align: center;
            animation: fadeIn 0.3s ease;
        }

        .popup-error {
            background-color: #ffe0e0;
            color: #b30000;
        }

        /* Overlay */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.5);
            width: 100%;
            height: 100%;
            z-index: 15;
            display: none;
            animation: fadeIn 0.3s ease;
        }

        /* Unsaved Modal */
        .unsaved-modal {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: linear-gradient(to bottom right, #ffffff, #e6f7ff);
            border: 2px solid #00C2FF;
            border-radius: 15px;
            padding: 30px 40px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
            z-index: 20;
            display: none;
            font-family: 'Mukta', sans-serif;
            animation: fadeIn 0.3s ease;
        }

        .unsaved-modal p {
            font-size: 20px;
            margin-bottom: 25px;
            color: #333;
        }

        .unsaved-modal button {
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin: 0 10px;
            transition: background-color 0.3s;
        }

        .unsaved-modal button:first-child {
            background-color: #28a745;
            color: white;
        }

        .unsaved-modal button:first-child:hover {
            background-color: #218838;
        }

        .unsaved-modal button:last-child {
            background-color: #dc3545;
            color: white;
        }

        .unsaved-modal button:last-child:hover {
            background-color: #c82333;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }


        .small-calendar {
            position: absolute;
            top: 100px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            border: 2px solid #00C2FF;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
            z-index: 1001;
            animation: fadeIn 0.3s ease;
            min-width: 300px;
            max-width: 95%;
            max-height: 90vh;
            overflow-y: auto;
        }


        .small-calendar h3 {
            text-align: center;
            margin-bottom: 15px;
            font-size: 20px;
            color: #333;
        }

        .small-calendar .month-grid,
        .small-calendar .day-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 15px;
        }

        .small-calendar .day-grid {
            grid-template-columns: repeat(7, 1fr);
            padding-bottom: 10px;
        }

        .small-calendar .month-grid div,
        .small-calendar .day-grid div {
            background: #e0f7ff;
            padding: 10px 0;
            text-align: center;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s ease;
            box-shadow: inset 0 0 0 1px #00C2FF;
        }

        .small-calendar .month-grid div:hover,
        .small-calendar .day-grid div:hover {
            background: #00C2FF;
            color: white;
            transform: scale(1.05);
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
        }

        .small-calendar .navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            gap: 10px;
        }

        .small-calendar .navigation button {
            background: #00C2FF;
            color: white;
            border: none;
            padding: 6px 16px;
            font-size: 14px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s ease;
            width: auto;
            min-width: 100px;
            align-self: flex-start;
            margin-left: 0;
        }



        .small-calendar .navigation button:hover {
            background: #009ad6;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .year-dropdown {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            max-height: 200px;
            overflow-y: auto;
            margin: 10px 0;
        }

        .year-option {
            background: #e0f7ff;
            padding: 8px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: inset 0 0 0 1px #00C2FF;
        }

        .year-option:hover {
            background: #00C2FF;
            color: white;
            transform: scale(1.05);
        }

        .calendar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.5);
            width: 100%;
            height: 100%;
            z-index: 1000;
            display: none;
        }

        .calendar-close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            color: red;
            background: transparent;
            border: none;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            z-index: 1002;
        }
    </style>
</head>

<body>
    <div id="calendarOverlay" class="calendar-overlay" onclick="closeSmallCalendar()"></div>
    <div id="overlay" class="overlay"></div>
    <div id="main-content">
        <div class="calendar-wrapper">
            <div class="week-header">
                <button class="arrow-btn" onclick="changeWeek(-1)">&lt;</button>
                <span id="weekRange" style="cursor: pointer;" onclick="showYearPicker()">Week: --</span>
                <button class="arrow-btn" onclick="changeWeek(1)">&gt;</button>
                <button class="today-btn" onclick="goToToday()">Today</button>
            </div>

            <div class="calendar-table">
                <div></div>
                <div class="header" id="day0"></div>
                <div class="header" id="day1"></div>
                <div class="header" id="day2"></div>
                <div class="header" id="day3"></div>
                <div class="header" id="day4"></div>
                <div class="header" id="day5"></div>
                <div class="header" id="day6"></div>

                <div class="time-label">7:00 – 15:00</div>
                <?php for ($i = 0; $i < 7; $i++) echo '<div class="calendar-cell"></div>'; ?>
                <div class="time-label">11:00 – 18:00</div>
                <?php for ($i = 0; $i < 7; $i++) echo '<div class="calendar-cell"></div>'; ?>
                <div class="time-label">15:00 – 22:00</div>
                <?php for ($i = 0; $i < 7; $i++) echo '<div class="calendar-cell"></div>'; ?>
            </div>

            <div class="radio-options">
                <label><input type="radio" name="availability" value="can"> Can</label>
                <label><input type="radio" name="availability" value="cant"> I can't</label>
                <label><input type="radio" name="availability" value="swap" id="swap-radio"> Swap</label>
            </div>

            <div class="send-button-wrapper">
                <button class="send-schedule-btn" onclick="sendSchedule()">Send Schedule</button>
            </div>

            <div id="popup" class="popup-message"></div>
            <div id="unsavedModal" class="unsaved-modal">
                <p>⚠️ You have unsaved changes!</p>
                <button onclick="confirmSave()">✅ Save and Send</button>
                <button onclick="discardChanges()">❌ Discard Changes</button>
            </div>

            <!-- Majhen koledar za izbiro dneva -->
            <div id="smallCalendar" class="small-calendar" style="display: none;"></div>
        </div>
    </div>


    <script>
        let currentDate = new Date();
        let selectedAvailability = null;
        let unsavedChanges = false;
        let calendarYear = new Date().getFullYear();
        let calendarMonth = new Date().getMonth();
        let calendarMode = null;

        function getMonday(d) {
            const date = new Date(d);
            const day = date.getDay();
            const diff = date.getDate() - day + (day === 0 ? -6 : 1);
            return new Date(date.setDate(diff));
        }

        function formatDate(date) {
            return date.toLocaleDateString('sl-SI', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }

        function updateWeek() {
            const monday = getMonday(currentDate);
            const sunday = new Date(monday);
            sunday.setDate(sunday.getDate() + 6);

            document.getElementById("weekRange").textContent =
                "Week: " + formatDate(monday) + " – " + formatDate(sunday);

            const dayNames = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            let isPastWeek = true;

            for (let i = 0; i < 7; i++) {
                const day = new Date(monday);
                day.setDate(monday.getDate() + i);

                const formatted =
                    dayNames[i] + "<br>" +
                    day.toLocaleDateString('sl-SI', {
                        day: '2-digit',
                        month: '2-digit'
                    }) + "<br>" +
                    (day.getMonth() + 1) + ".";

                const cell = document.getElementById('day' + i);
                cell.innerHTML = formatted;

                day.setHours(0, 0, 0, 0);

                if (day.getTime() === today.getTime()) {
                    cell.classList.add('today');
                } else {
                    cell.classList.remove('today');
                }

                if (day.getTime() >= today.getTime()) {
                    isPastWeek = false;
                }
            }

            const cells = document.querySelectorAll('.calendar-cell');
            const radios = document.querySelectorAll('input[name="availability"]');
            const sendButton = document.querySelector('.send-schedule-btn');

            cells.forEach(cell => {
                if (isPastWeek) {
                    cell.style.backgroundColor = '#ddd';
                    cell.style.pointerEvents = 'none';
                } else {
                    cell.style.backgroundColor = '#f0f0f0';
                    cell.style.pointerEvents = 'auto';
                }
            });

            radios.forEach(radio => {
                radio.disabled = isPastWeek;
            });

            sendButton.disabled = isPastWeek;
        }

        function changeWeek(amount) {
            if (unsavedChanges) {
                showUnsavedWarning();
                return;
            }
            currentDate.setDate(currentDate.getDate() + (amount * 7));
            updateWeek();
        }

        function goToToday() {
            if (unsavedChanges) {
                showUnsavedWarning();
                return;
            }
            currentDate = new Date();
            updateWeek();
        }

        document.querySelectorAll('input[name="availability"]').forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'swap' && unsavedChanges) {
                    this.checked = false;
                    showUnsavedWarning();
                    return;
                }
                selectedAvailability = this.value;
            });
        });

        document.querySelectorAll('.calendar-cell').forEach(cell => {
            cell.addEventListener('click', function() {
                if (!selectedAvailability) return;

                this.style.backgroundColor = '';
                this.style.borderColor = 'black';

                if (selectedAvailability === 'can') {
                    this.style.backgroundColor = '#c3f7c3';
                    this.style.borderColor = 'green';
                } else if (selectedAvailability === 'cant') {
                    this.style.backgroundColor = '#f7c3c3';
                    this.style.borderColor = 'red';
                } else if (selectedAvailability === 'swap') {
                    this.style.backgroundColor = '#fff3c3';
                    this.style.borderColor = 'orange';
                }

                if (selectedAvailability !== 'swap') {
                    unsavedChanges = true;
                }
            });
        });

        function sendSchedule() {
            unsavedChanges = false;
            document.getElementById("popup").textContent = "✅ Schedule sent successfully!";
            document.getElementById("popup").className = "popup-message";
            document.getElementById("popup").style.display = "block";

            setTimeout(() => {
                document.getElementById("popup").style.display = "none";
            }, 2500);

            hideOverlay();
        }

        function showUnsavedWarning() {
            document.getElementById("overlay").style.display = "block";
            document.getElementById("unsavedModal").style.display = "block";
        }

        function confirmSave() {
            document.getElementById("unsavedModal").style.display = "none";
            sendSchedule();
        }

        function discardChanges() {
            unsavedChanges = false;
            document.getElementById("unsavedModal").style.display = "none";
            hideOverlay();
        }

        function hideOverlay() {
            document.getElementById("overlay").style.display = "none";
        }

        window.addEventListener('beforeunload', function(e) {
            if (unsavedChanges) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // 🗓️ SMALL CALENDAR PICKER
        function showYearPicker() {
            calendarYear = currentDate.getFullYear();
            const calendarDiv = document.getElementById('smallCalendar');

            const yearOptions = Array.from({
                    length: 21
                }, (_, i) => 2015 + i)
                .map(y => `<div class="year-option" onclick="selectYear(${y})">${y}</div>`)
                .join('');

            calendarDiv.innerHTML = `
            <div class="calendar-close-btn" onclick="closeSmallCalendar()">✖</div>
        <h3 id="selectedYear" onclick="toggleYearDropdown()" style="cursor:pointer;">${calendarYear} ▼</h3>
        <div id="yearDropdown" class="year-dropdown" style="display:none;">${yearOptions}</div>
        <div class="month-grid">
            ${['Januar', 'Februar', 'Marec', 'April', 'Maj', 'Junij', 'Julij', 'Avgust', 'September', 'Oktober', 'November', 'December']
            .map((month, index) => `<div onclick="selectMonth(${index})">${month}</div>`).join('')}
        </div>
    `;
            document.getElementById('calendarOverlay').style.display = "block";
            calendarDiv.style.display = "block";
            calendarMode = 'year';
        }

        function toggleYearDropdown() {
            const dropdown = document.getElementById('yearDropdown');
            dropdown.style.display = dropdown.style.display === 'none' ? 'grid' : 'none';
        }

        function selectYear(year) {
            calendarYear = year;
            document.querySelector('#smallCalendar h3').innerHTML = `${calendarYear} ▼`;
            toggleYearDropdown();
        }



        function changeYear(amount) {
            calendarYear += amount;
            showYearPicker();
        }

        function selectMonth(month) {
            calendarMonth = month;
            showMonthDays(calendarYear, calendarMonth);
        }

        function showMonthDays(year, month) {
            const calendarDiv = document.getElementById('smallCalendar');
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const monthName = ['Januar', 'Februar', 'Marec', 'April', 'Maj', 'Junij', 'Julij', 'Avgust', 'September', 'Oktober', 'November', 'December'][month];

            let html = `
            <div class="calendar-close-btn" onclick="closeSmallCalendar()">✖</div>
            <h3>${monthName} ${year}</h3>
            <div class="navigation">
                <button onclick="showYearPicker()">⬅ Back</button>
            </div>
            <div class="day-grid">
        `;

            for (let day = 1; day <= daysInMonth; day++) {
                html += `<div onclick="selectDate(${year}, ${month}, ${day})">${day}</div>`;
            }

            html += `</div>`;
            calendarDiv.innerHTML = html;
        }

        function selectDate(year, month, day) {
            currentDate = new Date(year, month, day);
            updateWeek();
            closeSmallCalendar();
        }

        function closeSmallCalendar() {
            document.getElementById('smallCalendar').style.display = "none";
            document.getElementById('calendarOverlay').style.display = "none";
        }

        updateWeek();
    </script>

</body>

</html>