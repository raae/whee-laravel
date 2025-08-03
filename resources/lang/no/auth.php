<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'Disse legitimasjonene stemmer ikke med våre registre.',
    'password' => 'Det oppgitte passordet er feil.',
    'throttle' => 'For mange påloggingsforsøk. Vennligst prøv igjen om :seconds sekunder.',

    /*
    |--------------------------------------------------------------------------
    | One-Time Password Messages
    |--------------------------------------------------------------------------
    */

    'otp_errors' => [
        'no_passwords_found' => 'Ingen engangskode funnet. Vennligst be om ny kode.',
        'incorrect' => 'Ugyldig engangskode. Vennligst prøv igjen.',
        'different_origin' => 'Engangskoden kan ikke brukes fra denne enheten.',
        'expired' => 'Engangskoden er utløpt. Vennligst be om ny kode.',
        'rate_limit_exceeded' => 'For mange forsøk. Vennligst vent før du prøver igjen.',
        'default' => 'Det oppstod en feil med engangskoden. Vennligst prøv igjen.',
    ],

    'otp_success' => [
        'sent' => 'Engangskode sendt til ditt telefonnummer.',
        'verified' => 'Engangskode bekreftet. Du er nå logget inn.',
    ],

    'phone' => [
        'required' => 'Telefonnummer er påkrevd.',
        'invalid' => 'Telefonnummer må være et gyldig norsk nummer.',
        'not_registered' => 'Dette nummeret er ikke registrert i vårt system.',
        'verified' => 'Telefonnummer bekreftet.',
    ],
];
