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
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #3A82F7 0%, #00C2FF 100%);
            color: black;
            min-height: 100vh;
            padding-top: 80px;
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

        input[type="radio"][value="holiday"]:checked {
            accent-color: #6a5acd;
            /* recimo srednje vijolična kot “Lavender” */
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
            top: 40%;
            /* prej 50% */
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #e0ffe0;
            color: #1a6600;
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            font-size: 18px;
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
            position: fixed;
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


        /* Swap-modal ozadje */
        /* Zamenjaj ali dopolni obstoječi .swap-modal */
        .swap-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.6);
            /* temna prosojna podlaga */
            backdrop-filter: blur(4px);
            /* zameglitev ozadja */
            z-index: 2000;
        }

        /* vsebina ostane relativna znotraj */
        .swap-modal-content {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            max-width: 400px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            margin: auto;
            /* centriraj vsebino znotraj kvadrata */
            position: relative;
            top: 50%;
            transform: translateY(-50%);
            animation: fadeInScale 0.2s ease-out;
        }


        /* Po želji lahko dodajaš še nežne animacije */
        .swap-modal-content {
            animation: fadeInScale 0.2s ease-out;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }


        /* Križec za zapiranje */
        .swap-modal-close {
            position: absolute;
            top: 12px;
            right: 12px;
            background: transparent;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #555;
        }

        .swap-modal-close:hover {
            color: #000;
        }

        /* Naslov */
        .swap-modal-title {
            margin: 0 0 12px;
            font-size: 22px;
            color: #333;
            text-align: center;
        }

        /* Besedilo */
        .swap-modal-text {
            font-size: 16px;
            margin-bottom: 12px;
            color: #555;
        }

        /* Textarea */
        .swap-modal-textarea {
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 10px;
            font-size: 15px;
            resize: vertical;
            margin-bottom: 16px;
        }

        /* Footer za gumb */
        .swap-modal-footer {
            text-align: center;
        }

        /* Gumb */
        .swap-modal-btn {
            background: linear-gradient(45deg, #00C2FF, #3A82F7);
            border: none;
            border-radius: 8px;
            padding: 10px 24px;
            font-size: 16px;
            color: white;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
        }

        .swap-modal-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 194, 255, 0.6);
        }

        .swap-modal-btn:active {
            transform: scale(0.98);
        }

        #swapRequestsContainer {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            margin-top: 20px;
            max-width: 904px;
            /* 4 kartice po 320px + gap */
            margin-left: auto;
            margin-right: auto;
        }

        .swap-request-item {
            background: #fff;
            padding: 10px 14px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            font-size: 13px;
            color: #333;
            width: 220px;
            /* fiksna širina */
            margin: 2px;
            animation: fadeIn 0.3s ease-in-out;
            transition: all 0.2s ease;
        }

        .swap-request-item b {
            font-weight: 600;
            color: #000;
        }

        .swap-request-item .swap-actions {
            margin-top: 12px;
            display: flex;
            gap: 10px;
        }

        .swap-request-item .swap-actions button {
            padding: 8px 16px;
            font-size: 14px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            color: white;
            font-weight: 600;
            transition: background 0.25s, transform 0.15s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .swap-request-item .accept-btn {
            background: linear-gradient(45deg, #28a745, #44c765);
        }

        .swap-request-item .accept-btn:hover {
            background: linear-gradient(45deg, #218838, #3ecf74);
            transform: scale(1.03);
        }

        .swap-request-item .decline-btn {
            background: linear-gradient(45deg, #dc3545, #ff4e61);
        }

        .swap-request-item .decline-btn:hover {
            background: linear-gradient(45deg, #c82333, #ff3e50);
            transform: scale(1.03);
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
                <label><input type="radio" name="availability" value="holiday"> Holiday</label>
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

        <div id="swapRequestsContainer" style="margin-top: 40px;"></div>

        <!-- Swap Reason Modal -->
        <!-- Swap Reason Modal -->
        <div id="swapReasonModal" class="swap-modal" style="display:none;">
            <div class="swap-modal-content">
                <button class="swap-modal-close" onclick="closeSwapModal()">✖</button>

                <h2 class="swap-modal-title">Provide Swap Reason</h2>
                <p class="swap-modal-text">Please enter a reason why you cannot work on this day:</p>
                <textarea id="swapReasonText" class="swap-modal-textarea" rows="4" placeholder="Type your reason here..."></textarea>
                <div class="swap-modal-footer">
                    <button id="swapReasonSendBtn" class="swap-modal-btn">Send</button>
                </div>
            </div>
        </div>


    </div>


    <script>
        // — Helpers za barvanje in swap-gumb —
        function updateSwapButton() {
            const anyCan = cells.some(cell => isGreen(cell));
            const swapRadio = document.getElementById('swap-radio');
            swapRadio.disabled = !anyCan;
            swapRadio.parentElement.style.opacity = anyCan ? '1' : '0.5';
        }

        function colorCell(cell, availability) {
            cell.style.backgroundColor = '';
            cell.style.borderColor = 'black';
            if (availability === 'can') {
                cell.style.backgroundColor = '#c3f7c3';
                cell.style.borderColor = 'green';
            } else if (availability === 'cant') {
                cell.style.backgroundColor = '#f7c3c3';
                cell.style.borderColor = 'red';
            } else if (availability === 'swap') {
                cell.style.backgroundColor = '#fff3c3';
                cell.style.borderColor = 'orange';
            } else if (availability === 'holiday') {
                cell.style.backgroundColor = '#e0e0ff';
                cell.style.borderColor = '#6a5acd';
            }
            updateSwapButton();
        }

        // — Utility za preverjanje zelene celice —
        function isGreen(cell) {
            const bc = window.getComputedStyle(cell).borderColor;
            return bc === 'green' || bc === 'rgb(0, 128, 0)';
        }

        // — Spremenljivke za koledar in range —
        let currentDate = new Date();
        let selectedAvailability = null;
        let unsavedChanges = false;
        let calendarYear = new Date().getFullYear();
        let calendarMonth = new Date().getMonth();
        let calendarMode = null;
        let rangeStart = null;
        let startCoord = null;

        // — Pridobimo vse celice –
        const cells = Array.from(document.querySelectorAll('.calendar-cell'));

        const swapReasonModal = document.getElementById('swapReasonModal');
        const swapReasonText = document.getElementById('swapReasonText');
        const swapReasonSendBtn = document.getElementById('swapReasonSendBtn');


        // — Osnovne funkcije za teden in datum —
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

                const header = document.getElementById('day' + i);
                header.innerHTML =
                    dayNames[i] + "<br>" +
                    day.toLocaleDateString('sl-SI', {
                        day: '2-digit',
                        month: '2-digit'
                    }) + "<br>" +
                    (day.getMonth() + 1) + ".";

                day.setHours(0, 0, 0, 0);
                header.classList.toggle('today', day.getTime() === today.getTime());
                if (day.getTime() >= today.getTime()) isPastWeek = false;
            }

            cells.forEach(cell => {
                if (isPastWeek) {
                    cell.style.backgroundColor = '#ddd';
                    cell.style.pointerEvents = 'none';
                } else {
                    cell.style.backgroundColor = '#f0f0f0';
                    cell.style.pointerEvents = 'auto';
                }
                cell.style.opacity = '1';
            });
            document.querySelectorAll('input[name="availability"]').forEach(radio => {
                radio.disabled = isPastWeek;
            });
            document.querySelector('.send-schedule-btn').disabled = isPastWeek;
            updateSwapButton();
        }

        function changeWeek(amount) {
            if (unsavedChanges) {
                showUnsavedWarning();
                return;
            }
            currentDate.setDate(currentDate.getDate() + amount * 7);
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

        let prevAvailability = null;

        document.querySelectorAll('input[name="availability"]').forEach(radio => {
            radio.addEventListener('focus', function() {
                prevAvailability = selectedAvailability;
            });

            radio.addEventListener('change', function(e) {
                const newVal = this.value;

                // ⛔ Če obstajajo neshranjene spremembe in uporabnik gre iz can/cant → swap/holiday
                const goingToRestricted = (newVal === 'swap' || newVal === 'holiday');
                const comingFromCanCant = (prevAvailability === 'can' || prevAvailability === 'cant');

                if (unsavedChanges && comingFromCanCant && goingToRestricted) {
                    e.preventDefault();
                    // resetiraj radio na prejšnjega
                    if (prevAvailability) {
                        document.querySelector(`input[value="${prevAvailability}"]`).checked = true;
                    } else {
                        this.checked = false;
                    }
                    showUnsavedWarning();
                    return;
                }

                // ✅ Shrani novo izbiro, če ni omejitev
                selectedAvailability = newVal;

                // swap logika: potemni vse celice razen zelenih
                if (newVal === 'swap') {
                    cells.forEach(c => {
                        if (!isGreen(c)) {
                            c.style.opacity = '0.3';
                            c.style.pointerEvents = 'none';
                        } else {
                            c.style.opacity = '1';
                            c.style.pointerEvents = 'auto';
                        }
                    });
                } else {
                    // za vse ostale – normalno
                    cells.forEach(c => {
                        c.style.opacity = '1';
                        c.style.pointerEvents = 'auto';
                    });
                }
            });
        });


        let lastSelectedGreenCell = null; // celica, ki jo je uporabnik kliknil v swap načinu

        function closeSwapModal() {
            // skrij modal
            swapReasonModal.style.display = 'none';
            // počisti besedilo
            swapReasonText.value = '';

            // če obstaja celica, ki je bila kliknjena kot swap, jo vrni v zeleno
            if (lastSelectedGreenCell) {
                colorCell(lastSelectedGreenCell, 'can');
                lastSelectedGreenCell = null;
            }
        }


        // — Click-handler za vse primere —
        cells.forEach((cell, idx) => {
            cell.addEventListener('click', function() {
                if (!selectedAvailability) return;

                // 1) HOLIDAY: range logika
                if (selectedAvailability === 'holiday') {
                    const day = idx % 7;
                    const slot = Math.floor(idx / 7);
                    if (rangeStart === null) {
                        rangeStart = idx;
                        startCoord = {
                            day,
                            slot
                        };
                        colorCell(cell, 'holiday');
                    } else {
                        const endCoord = {
                            day,
                            slot
                        };
                        const dayMin = Math.min(startCoord.day, endCoord.day);
                        const dayMax = Math.max(startCoord.day, endCoord.day);
                        const slotMin = Math.min(startCoord.slot, endCoord.slot);
                        const slotMax = Math.max(startCoord.slot, endCoord.slot);
                        for (let s = slotMin; s <= slotMax; s++) {
                            for (let d = dayMin; d <= dayMax; d++) {
                                colorCell(cells[s * 7 + d], 'holiday');
                            }
                        }
                        rangeStart = null;
                        startCoord = null;
                        unsavedChanges = true;
                    }
                    return;
                }

                // 2) SWAP: samo na že zelenih
                if (selectedAvailability === 'swap') {
                    // deluj samo na že zelenih celicah
                    if (!isGreen(cell)) return;

                    // odpri modal za vnos razloga
                    swapReasonModal.style.display = 'block';
                    lastSelectedGreenCell = cell;

                    // ob kliku na Send v modalu
                    swapReasonSendBtn.onclick = () => {
                        const reason = swapReasonText.value.trim();
                        if (!reason) {
                            alert('Please enter a reason in English.');
                            return;
                        }

                        colorCell(lastSelectedGreenCell, 'swap');
                        unsavedChanges = true;
                        updateSwapButton();

                        // 🆕 Dodaj zahtevo pod koledar
                        addSwapRequest(cells.indexOf(lastSelectedGreenCell), reason);

                        // Počisti modal
                        swapReasonText.value = '';
                        swapReasonModal.style.display = 'none';
                        lastSelectedGreenCell = null;
                    };


                    return;
                }


                // 3) CAN / CANT
                if (selectedAvailability === 'can' || selectedAvailability === 'cant') {
                    colorCell(cell, selectedAvailability);
                    unsavedChanges = true;
                    return;
                }
            });
        });

        // — Ostale funkcije za popup in picker (pusti nespremenjeno) —
        function sendSchedule() {
            unsavedChanges = false;
            document.getElementById("popup").textContent = "✅ Schedule sent successfully!";
            document.getElementById("popup").className = "popup-message";
            document.getElementById("popup").style.display = "block";
            setTimeout(() => document.getElementById("popup").style.display = "none", 1000);
            hideOverlay();
            updateSwapButton();
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
            updateSwapButton();
        }

        function hideOverlay() {
            document.getElementById("overlay").style.display = "none";
        }

        // 🗓️ SMALL-CALENDAR PICKER funkcije in beforeunload (pusti kot je)
        window.addEventListener('beforeunload', function(e) {
            if (unsavedChanges) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

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
    <h3 id="selectedYear" onclick="toggleYearDropdown()" style="cursor:pointer;">
      ${calendarYear} ▼
    </h3>
    <div id="yearDropdown" class="year-dropdown" style="display:none;">
      ${yearOptions}
    </div>
    <div class="month-grid">
      ${['Januar','Februar','Marec','April','Maj','Junij','Julij','Avgust','September','Oktober','November','December']
        .map((m,i) => `<div onclick="selectMonth(${i})">${m}</div>`).join('')}
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



        function addSwapRequest(cellIndex, reason) {
            const slotLabels = ["7:00–15:00", "11:00–18:00", "15:00–22:00"];
            const dayIndex = cellIndex % 7;
            const slotIndex = Math.floor(cellIndex / 7);

            const header = document.getElementById('day' + dayIndex);
            const dateText = header.textContent.split('\n')[1] || header.innerHTML.split('<br>')[1];

            const container = document.getElementById("swapRequestsContainer");

            const item = document.createElement("div");
            item.className = "swap-request-item";
            item.innerHTML = `
    <strong>Date:</strong> ${dateText} &nbsp;&nbsp;
    <strong>Shift:</strong> ${slotLabels[slotIndex]}<br>
    <strong>Reason:</strong> ${reason}
    <div class="swap-request-buttons">
      <button class="accept-btn">✅ Accept</button>
      <button class="decline-btn">❌ Decline</button>
    </div>
  `;

            container.appendChild(item);

            // Buttons
            const acceptBtn = item.querySelector(".accept-btn");
            const declineBtn = item.querySelector(".decline-btn");

            acceptBtn.onclick = () => {
                cells[cellIndex].style.backgroundColor = '#c3f7c3';
                cells[cellIndex].style.borderColor = 'green';
                item.remove();
            };

            declineBtn.onclick = () => {
                item.remove();
            };
        }



        // Inicializiraj
        updateWeek();


        function startFullCalendarTutorial() {
            const tutorial = introJs();

            tutorial.setOptions({
                showStepNumbers: false,
                exitOnOverlayClick: false,
                hidePrev: true,
                steps: [{
                        intro: "👋 Welcome to the full Schedulizer tutorial! Click <b>Next</b> to begin."
                    },
                    {
                        element: document.querySelector('.radio-options'),
                        intro: "Step 1: Select <b>I can</b> – this means you're available to work.",
                        position: 'bottom'
                    },
                    {
                        element: document.querySelector('.calendar-table'),
                        intro: "Now click on <b>one cell</b> to mark it green.",
                        position: 'top'
                    },
                    {
                        element: document.querySelector('.radio-options'),
                        intro: "Select <b>I can’t</b> – this means you’re not available.",
                        position: 'bottom'
                    },
                    {
                        element: document.querySelector('.calendar-table'),
                        intro: "Click a <b>different cell</b> to mark it red.",
                        position: 'top'
                    },
                    {
                        element: document.querySelector('.send-button-wrapper'),
                        intro: "✅ Now you <b>MUST click Save</b> to confirm your availability.<br><br>This is required before using Swap or Holiday features – otherwise your choices won’t be saved!",
                        position: 'top'
                    },
                    {
                        element: document.querySelector('.radio-options'),
                        intro: "Now select <b>Swap</b> mode to request a shift change.",
                        position: 'bottom'
                    },
                    {
                        element: document.querySelector('.calendar-table'),
                        intro: "Click on a <b>green cell</b> to start a swap request.",
                        position: 'top'
                    },
                    {
                        intro: "✍️ To request a shift swap, you must first write a reason in the popup and click <b>Send</b>.<br><br>" +
                            "📨 After sending, your request will appear below the calendar for others to see.<br>" +
                            "✅ If someone accepts, their cell turns green and yours turns red.<br>" +
                            "⏳ If no one accepts, the cell remains green and your shift stays active.<br>" +
                            "🟨 Until a response is given, the cell stays yellow as pending."
                    },
                    {
                        element: document.querySelector('.radio-options'),
                        intro: "Select <b>Holiday</b> mode to request time off.",
                        position: 'bottom'
                    },
                    {
                        element: document.querySelector('.calendar-table'),
                        intro: "Click the <b>start and end</b> cell to mark your holiday range.",
                        position: 'top'
                    },
                    {
                        element: document.querySelector('.send-button-wrapper'),
                        intro: "Click Save to confirm your holiday.",
                        position: 'top'
                    },
                    {
                        element: document.querySelector('.week-header'),
                        intro: "Use these arrows to move between weeks. 📅",
                        position: 'bottom'
                    },
                    {
                        intro: "🎉 That’s it! You’ve completed the full calendar tutorial. Well done!<br><br>" +
                            "<div style='text-align: right; margin-top: 20px;'>" +
                            "<button onclick='exitTutorial()' style='padding: 8px 18px; background-color: #00C2FF; color: white; border: none; border-radius: 6px; font-size: 15px; cursor: pointer;'>✅ Done</button>" +
                            "</div>"
                    }
                ]
            });

            tutorial.onafterchange(function() {
                const step = this._currentStep;

                if (step === 1) {
                    const radio = document.querySelector('input[type="radio"][value="can"]');
                    radio.addEventListener('click', () => this.nextStep(), {
                        once: true
                    });
                    this._options.hideNext = true;
                }

                if (step === 2) {
                    let clicked = false;
                    document.querySelectorAll('.calendar-cell').forEach(cell => {
                        cell.addEventListener('click', () => {
                            if (!clicked) {
                                clicked = true;
                                this.nextStep();
                            }
                        }, {
                            once: true
                        });
                    });
                    this._options.hideNext = true;
                }

                if (step === 3) {
                    const radio = document.querySelector('input[type="radio"][value="cant"]');
                    radio.addEventListener('click', () => this.nextStep(), {
                        once: true
                    });
                    this._options.hideNext = true;
                }

                if (step === 4) {
                    let clicked = false;
                    document.querySelectorAll('.calendar-cell').forEach(cell => {
                        cell.addEventListener('click', () => {
                            if (!clicked) {
                                clicked = true;
                                this.nextStep();
                            }
                        }, {
                            once: true
                        });
                    });
                    this._options.hideNext = true;
                }

                if (step === 5) {
                    const sendBtn = document.querySelector('.send-schedule-btn');
                    sendBtn.addEventListener('click', () => {
                        setTimeout(() => this.nextStep(), 800);
                    }, {
                        once: true
                    });
                    this._options.hideNext = true;
                }

                if (step === 6) {
                    const radio = document.querySelector('input[type="radio"][value="swap"]');
                    radio.addEventListener('click', () => this.nextStep(), {
                        once: true
                    });
                    this._options.hideNext = true;
                }

                if (step === 7) {
                    let clicked = false;
                    document.querySelectorAll('.calendar-cell').forEach(cell => {
                        cell.addEventListener('click', () => {
                            if (!clicked) {
                                clicked = true;
                                setTimeout(() => this.nextStep(), 500);
                            }
                        }, {
                            once: true
                        });
                    });
                    this._options.hideNext = true;
                }

                if (step === 8) {
                    document.querySelector('.swap-modal')?.classList.add('hidden');
                }

                if (step === 9) {
                    const swapModal = document.querySelector('.swap-modal');
                    if (swapModal) swapModal.style.display = 'none';
                    const radio = document.querySelector('input[type="radio"][value="holiday"]');
                    radio.addEventListener('click', () => this.nextStep(), {
                        once: true
                    });
                    this._options.hideNext = true;
                }

                if (step === 10) {
                    let clicks = 0;
                    document.querySelectorAll('.calendar-cell').forEach(cell => {
                        cell.addEventListener('click', () => {
                            clicks++;
                            if (clicks === 2) {
                                setTimeout(() => this.nextStep(), 400);
                            }
                        }, {
                            once: true
                        });
                    });
                    this._options.hideNext = true;
                }

                if (step === 11) {
                    const sendBtn = document.querySelector('.send-schedule-btn');
                    sendBtn.addEventListener('click', () => {
                        setTimeout(() => this.nextStep(), 600);
                    }, {
                        once: true
                    });
                    this._options.hideNext = true;
                }

                if (step === 12) {
                    document.querySelectorAll('.arrow-btn').forEach(btn => {
                        btn.addEventListener('click', () => this.nextStep(), {
                            once: true
                        });
                    });
                    this._options.hideNext = true;
                }
            });

            tutorial.start();
        }

        // 🔵 Funkcija za "Done" gumb na koncu
        function exitTutorial() {
            introJs().exit();
            window.location.href = "calendar.php"; // brez ?tutorial=true
        }

        // 🔁 Samodejni zagon, če URL vsebuje ?tutorial=true
        document.addEventListener("DOMContentLoaded", function() {
            const params = new URLSearchParams(window.location.search);
            if (params.get("tutorial") === "true") {
                setTimeout(() => {
                    startFullCalendarTutorial();
                }, 500);
            }
        });
    </script>


</body>

</html>