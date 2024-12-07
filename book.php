<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "booking_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$services_sql = "SELECT * FROM Services";
$services_result = $conn->query($services_sql);

if (!$services_result) {
    die("Error fetching services: " . $conn->error);
}

$therapists_sql = "SELECT * FROM Users WHERE role = 'therapist'";
$therapists_result = $conn->query($therapists_sql);

if (!$therapists_result) {
    die("Error fetching therapists: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.2.0/fullcalendar.min.css">
    <style>
    body {
        background: linear-gradient(to bottom, #FDF7F4, #8EB486, #997C70, #685752);
        color: #333;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .card {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        border-radius: 10px;
        margin-top: 30px;
        background: #fff;
    }
    .card-body {
        padding: 20px;
    }
    #time-slots {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
    }
    .time-slot {
        padding: 10px 20px;
        border-radius: 5px;
        margin: 5px;
        font-weight: bold;
        background-color: #8EB486; 
        color: #fff;
        border: none;
        transition: transform 0.2s ease-in-out, background-color 0.3s;
    }
    .time-slot:hover {
        transform: scale(1.1);
        background-color: #997C70; 
    }
    .time-slot.active {
        background-color: #997C70;
        font-weight: bold;
    }
    </style>
</head>
<body>

<div class="container">
    <div class="card" id="step1">
        <div class="card-body">
            <h4>Select Service & Therapist</h4>
            <form id="bookingForm">
                <div class="mb-3">
                    <label for="service" class="form-label">Service</label>
                    <select id="service" class="form-select" required>
                        <option value="">Select a service</option>
                        <?php while ($service = $services_result->fetch_assoc()): ?>
                            <option value="<?= $service['service_id'] ?>" data-price="<?= $service['price'] ?>"><?= $service['service_name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="therapist" class="form-label">Therapist</label>
                    <select id="therapist" class="form-select" required>
                        <option value="">Select a therapist</option>
                        <?php while ($therapist = $therapists_result->fetch_assoc()): ?>
                            <option value="<?= $therapist['user_id'] ?>"><?= $therapist['full_name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="button" id="nextButton" class="btn btn-primary btn-next" disabled>Next</button>
            </form>
        </div>
    </div>

    <div class="card calendar-container" id="step2">
        <div class="card-body">
            <h4>Select Appointment Date</h4>
            <div id="calendar"></div>
            <p id="appointment-date-display" class="mt-3"></p>
            <button type="button" class="btn btn-secondary btn-previous">Previous</button>
        </div>
    </div>

    <div class="card time-container" id="step3">
        <div class="card-body">
            <h4>Select Appointment Time</h4>
            <div id="time-slots"></div>
            <p id="appointment-time-display" class="mt-3"></p>
            <button type="button" class="btn btn-secondary btn-previous-time">Previous</button>
            <button type="button" class="btn btn-primary btn-next-time">Next</button>
        </div>
    </div>

    <div class="card confirmation-container" id="step4">
        <div class="card-body">
            <h4>Confirm Appointment</h4>
            <p><strong>Service:</strong> <span id="service-summary"></span></p>
            <p><strong>Therapist:</strong> <span id="therapist-name"></span></p>
            <p><strong>Appointment Time:</strong> <span id="appointment-time"></span></p>
            <button type="button" class="btn btn-success" id="confirm-appointment">Confirm Appointment</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.2.0/fullcalendar.min.js"></script>
<script>
    $(document).ready(function () {
        let selectedService, selectedTherapist, selectedDate, selectedTime, serviceName, therapistName;

        $('#service, #therapist').change(function () {
            selectedService = $('#service').val();
            selectedTherapist = $('#therapist').val();
            serviceName = $('#service option:selected').text();
            therapistName = $('#therapist option:selected').text();
            $('#nextButton').prop('disabled', !selectedService || !selectedTherapist);
        });

        $('#nextButton').click(function () {
            $('#step1').hide();
            $('#step2').show();
        });

        $('#calendar').fullCalendar({
            selectable: true,
            select: function (start) {
                selectedDate = start.format('YYYY-MM-DD');
                $('#appointment-date-display').text('Selected Date: ' + selectedDate);
                $('#step2').hide();
                $('#step3').show();
                loadAvailableTimeSlots();
            }
        });

        function loadAvailableTimeSlots() {
            const slots = Array.from({ length: 13 }, (_, i) => moment().hours(8 + i).minutes(0).format('HH:mm'));
            $('#time-slots').html(slots.map(slot =>
                `<button type="button" class="btn btn-outline-primary time-slot ${slot === selectedTime ? 'active' : ''}" data-time="${slot}">${slot}</button>`).join(''));
        }

        $(document).on('click', '.time-slot', function () {
            $('.time-slot').removeClass('active');
            $(this).addClass('active');
            selectedTime = $(this).data('time');
            $('#appointment-time-display').text('Selected Time: ' + selectedTime);
        });

        $('.btn-next-time').click(function () {
            if (selectedDate && selectedTime) {
                $('#step3').hide();
                $('#step4').show();
                $('#service-summary').text(serviceName);
                $('#therapist-name').text(therapistName);
                $('#appointment-time').text(selectedDate + ' at ' + selectedTime);
            } else {
                alert('Please select a date and time.');
            }
        });

        $('.btn-previous-time').click(function () {
            $('#step3').hide();
            $('#step2').show();
        });

        $('.btn-previous').click(function () {
            $('#step2').hide();
            $('#step1').show();
        });

        $('#confirm-appointment').click(function () {
            $.post('confirm_appointment.php', {
                service_id: selectedService,
                therapist_id: selectedTherapist,
                appointment_date: selectedDate,
                start_time: selectedTime,
                end_time: moment(selectedTime, 'HH:mm').add(1, 'hour').format('HH:mm')
            }, function (response) {
                const data = JSON.parse(response);
                if (data.status === 'success') {
                    window.location.href = 'index.php';
                } else {
                    alert(data.message);
                }
            }).fail(() => alert('Error confirming appointment.'));
        });
    });
</script>
</body>
</html>