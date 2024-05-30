<?php
/**
 * English translations for assitance page
 * 
 * @author      Orif (KoYo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (http://www.orif.ch)
 */

 return[

    /* Roles explaination */
    'roles_summary' => 'Roles',
    'roles_details' => 
    '<p>Roles define the responsabilites of technicians when managing the helpdesk.</p>
    <p>There are 3 levels :</p>
    <ul>
        <li>On call technician</li>
        <li>Back up technician</li>
        <li>Standby technician</li>
    </ul>
    <p>
        The <span class="bg-green">On call technician</span> responds to clients. Whether by phone or directly.<br>
        The <span class="bg-light-green">Back up technician</span> responds to clients while the on call technician is absent.<br>
        The <span class="bg-orange">Standby technician</span> responds to clients while the on call technician <strong>and</strong> the Back up technician is absent.
    </p>
    <p>
        If no technician is assigned or available for a period, another person must manage the helpdesk.
    </p>',

    /* Presences explaination */
    'presences_summary' => 'Availabilities',
    'presences_details' => '
    <p>The presence of technicians makes it possible to indicate the availability of technicians during the periods of the week.</p>
    <p>There are 3 possible options :</p>
    <ul>
        <li>Available</li>
        <li>Absent in part</li>
        <li>Absent</li>
    </ul>
    <p>
        Being <span class="present">available</span> means that during the whole period, the technician is in the work area.<br>
        Being <span class="partly-absent">absent in part</span> means that during the majority of the time(not entirely), 
        the technician is in the work area.<br>
        Being <span class="absent">absent</span> means that during the whole period, the technician <strong>is not</strong> 
        in the work area.
    </p>
    <p>
        The availabilites are important to fill : it is impossible in the schedule to add in or generate a schedule for a technician with no availabilites.<br>
        During the modification of the schedule, the option "ignore verification of availability" allows bypassing those restrictions. 
        Only to be used in case of necessity.
    </p>',

    /* Mentor explaination */
    'mentor_summary' => 'Mentor technician',
    'mentor_details' =>
    '<p>The mentor technician is an experienced technician with the mission of trainning new technicians at the helpdesk.</p>
    <p>The latters are supported by the mentor technician thanks to his sound advice throught the request/incident management :</p>
    <ul>
        <li>Call management</li>
        <li>Ticket creation</li>
        <li>On-site intervention</li>
    </ul>
    <p>
        In principle, the mentor technician does not do the work instead of the new technician (Except if they need help of course).
    </p>
    <p>
        On this site, Mentor technicians are distinguished in the schedule by a blue strip inside the box containing their first and last name.
    </p>'
];