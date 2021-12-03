<table>
 <thead>
 <tr>
  <th>Student Number</th>
  <th>First Name</th>
  <th>Middle Name</th>
  <th>Last Name</th>
  <th>Ext. Name</th>
  <th>Date of Birth</th>
  <th>Primary Contact Number</th>
  <th>Secondary Contact Number</th>
  <th>Primary Email</th>
  <th>is Verified</th>
  <th>Secondary Email</th>
  <th>is Verified</th>
  <th>Program</th>
  <th>Year Level</th>
  <th>TES Award</th>
  <th>TES Application Number</th>
  <th>TES Grant Type</th>
  <th>Academic Units</th>
  <th>GWA</th>
  <th>Street</th>
  <th>Barangay</th>
  <th>Town</th>
  <th>Province</th>
  <th>Zipcode</th>
 </tr>
 </thead>
 <tbody>
 @foreach($applicants as $applicant)
     <tr>
         <td>{{ $applicant->student_number }}</td>
         <td>{{ $applicant->info->first_name }}</td>
         <td>{{ $applicant->info->middle_name }}</td>
         <td>{{ $applicant->info->last_name }}</td>
         <td>{{ $applicant->info->ext_name }}</td>
         <td>{{ $applicant->info->birthday }}</td>
         <td>{{ $applicant->info->contact_number }}</td>
         <td>{{ $applicant->info->contact_number2 }}</td>
         <td>{{ $applicant->email }}</td>
         <td>{{ $applicant->email_verified_at ? 'Verified' : 'Pending' }}</td>
         <td>{{ $applicant->email_secondary }}</td>
         <td>{{ $applicant->email_secondary_verified_at ? 'Verified' : 'Pending' }}</td>
         <td>{{ $applicant->info->program }}</td>
         <td>{{ $applicant->info->year_level }}</td>
         <td>{{ $applicant->info->tes_award }}</td>
         <td>{{ $applicant->info->tes_application_number }}</td>
         <td>{{ $applicant->info->tes_grant_type }}</td>
         <td>{{ $applicant->info->academic_units }}</td>
         <td>{{ $applicant->info->gwa }}</td>
         <td>{{ $applicant->info->street }}</td>
         <td>{{ $applicant->info->barangay }}</td>
         <td>{{ $applicant->info->town }}</td>
         <td>{{ $applicant->info->province }}</td>
         <td>{{ $applicant->info->zipcode }}</td>
     </tr>
 @endforeach
 </tbody>
</table>