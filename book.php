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
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            margin-top: 30px;
        }

        .card-body {
            padding: 20px;
        }

        .btn-next, .btn-previous {
            margin-top: 20px;
        }

        .calendar-container, .time-container, .confirmation-container {
            display: none;
            margin-top: 20px;
        }

        .confirmation-card {
            margin-top: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        }

        .confirmation-details {
            padding: 20px;
        }

        .confirmation-details h5 {
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
            <button type="button" class="btn btn-secondary btn-previous">Previous</button>
        </div>
    </div>

   
    <div class="card time-container" id="step3">
        <div class="card-body">
            <h4>Select Appointment Time</h4>
            <div id="time-slots"></div>
            <button type="button" class="btn btn-secondary btn-previous-time">Previous</button>
            <button type="button" class="btn btn-primary btn-next-time" disabled>Next</button>
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

            if (selectedService && selectedTherapist) {
                $('#nextButton').prop('disabled', false);
            } else {
                $('#nextButton').prop('disabled', true);
            }
        });

       
        $('#nextButton').click(function () {
            $('#step1').hide();
            $('#step2').show();
        });

       
        $('#calendar').fullCalendar({
            selectable: true,
            select: function (start) {
                selectedDate = start.format('YYYY-MM-DD');
                $('#appointment-time').text('Selected date: ' + selectedDate);
                $('#step2').hide();
                $('#step3').show();
            }
        });

       
        function loadAvailableTimeSlots() {
            const slots = [];
            for (let i = 8; i <= 20; i++) {
                let time = moment().hours(i).minutes(0).format('HH:mm');
                slots.push(time);
            }
            let slotHtml = '';
            slots.forEach(slot => {
                slotHtml += `<button type="button" class="btn btn-outline-primary time-slot" data-time="${slot}">${slot}</button>`;
            });
            $('#time-slots').html(slotHtml);
        }

       
        loadAvailableTimeSlots();

      
        $(document).on('click', '.time-slot', function () {
            selectedTime = $(this).data('time');
            $('#appointment-time').text('Selected date: ' + selectedDate + ' at ' + selectedTime);
            $('#step3').hide();
            $('#step4').show();
            
          
            $('#service-summary').text(serviceName);
            $('#therapist-name').text(therapistName);
            $('#appointment-time').text('Appointment: ' + selectedDate + ' at ' + selectedTime);
        });

      
        $(".btn-previous-time").click(function () {
            $('#step3').hide();
            $('#step2').show();
        });


        $(".btn-previous").click(function () {
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
                var data = JSON.parse(response);
                if (data.status === 'success') {
                    
                    window.location.href = 'index.php';
                } else {
                    alert(data.message);
                }
            }).fail(function () {
                alert('Error confirming appointment.');
            });
        });
    });
</script>
</body>
</html>