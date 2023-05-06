@extends('_layout')


@section('content')
    <div class="container">
        <div class="row vh-100 justify-content-center align-items-center">
            <div class="col-md-4 col-12">
                <h2 class="text-center">Verify your phone number</h2>
                <form class="mt-3">
                    <div class="alert alert-danger mb-3" style="display:none;" id="error"></div>
                    <div class="alert alert-success mb-3" id="phoneNumberSuccess" style="display:none;"></div>
                    <div id="timer" style="display:none; font-size:14px;" class="text-center text-danger mt-3">
                    </div>
                    <label>Enter Phone Number</label>
                    <div class="input-group flex-nowrap mt-2">
                        <span class="input-group-text" id="addon-wrapping">+88</span>
                        <input type="text" class="form-control" placeholder="01*********" aria-label="phone"
                            aria-describedby="addon-wrapping" name="phone" id="phone">
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <div id="recaptcha-container"></div>
                    </div>
                    <div class="d-grid mt-3">
                        <button class="btn btn-success rounded-pill" type="button" onclick="sendCode();" id="sendCodeId">Send Code</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"
        integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>

    <script>
        // Initialize Firebase
        // TODO: Replace with your project's customized code snippet
        const firebaseConfig = {
            apiKey: "AIzaSyDt-AfKx1caCpAxsnIHElZ02VGqQWW8avw",
            authDomain: "fir-send-otp-5e683.firebaseapp.com",
            projectId: "fir-send-otp-5e683",
            storageBucket: "fir-send-otp-5e683.appspot.com",
            messagingSenderId: "577828239323",
            appId: "1:577828239323:web:782ac5b91c4697e47219fe",
            measurementId: "G-KTLKWV9LTK"
        };

        // Initialize Firebase
        const app = firebase.initializeApp(firebaseConfig);
    </script>

    <script>
        window.onload = function() {
            render();
        }

        function render() {
            window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
            recaptchaVerifier.render();
        }

        function getPhoneNumberFromUserInput() {
            return '+88' + $('#phone').val();
        }

        function isCaptchaChecked() {
            return grecaptcha && grecaptcha.getResponse().length !== 0;
        }

        function showMessage(id, message) {
            $(id).show();
            $(id).text(message);
        }

        function hideMessage(ids) {
            ids.forEach(function(id) {
                $(id).hide();
            });
        }

        function setTimer(time, expire_id) {
            let countDownDate = new Date(time).getTime();
            $('#timer').show();

            // Update the count down every 1 second
            let x = setInterval(function() {

                // Get today's date and time
                let now = new Date().getTime();

                // Find the distance between now and the count down date
                let distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Output the result in an element with id="demo"
                
                document.getElementById("timer").innerHTML = 
                    '<h4>Enter Verification Code Before</h4>'+
                    minutes + " : " + seconds + "s"
                ;

                
                
                // If the count down is over, write some text 
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("timer").innerHTML = "EXPIRED";
                    document.getElementById("sendCodeId").disabled = false;
                    $.ajax({
                            url: "{{ route('expire.Confirmed.Phone') }}",
                            method: "POST",
                            data: {
                                id: expire_id
                            },
                            dataType: 'JSON',
                            headers: {
                                'X-CSRF-TOKEN': $('input[name="_token"]').val()
                            },
                            success: function(data) {
                                console.log(data);
                                
                            },
                            error: function(data) {
                                console.log(data.responseJSON);
                            }
                        });
                }
            }, 1000);
        }

        function sendCode() {
            const phoneNumber = getPhoneNumberFromUserInput();
            if (!isCaptchaChecked()) {
                hideMessage(['#error', '#phoneNumberSuccess', '#verificationError', '#verifySuccess']);
                showMessage('#error', 'reCaptcha is not selected');
            } else {
                firebase.auth().signInWithPhoneNumber(phoneNumber, recaptchaVerifier)
                    .then((confirmationResult) => {
                        // SMS sent. Prompt user to type the code from the message, then sign the
                        // user in with confirmationResult.confirm(code).
                        window.confirmationResult = confirmationResult;
                        $.ajax({
                            url: "{{ route('storeConfirmedPhone') }}",
                            method: "POST",
                            data: {
                                phone: phoneNumber,
                                id: {{ $id }}
                            },
                            dataType: 'JSON',
                            headers: {
                                'X-CSRF-TOKEN': $('input[name="_token"]').val()
                            },
                            success: function(data) {
                                console.log(data);
                                document.getElementById("sendCodeId").disabled = true;
                                setTimer(data.time);
                            },
                            error: function(data) {
                                console.log(data.responseJSON);
                            }
                        });
                    }).catch((error) => {
                        // Error; SMS not sent
                        // ...
                    });
            }

        }
    </script>
@endsection
