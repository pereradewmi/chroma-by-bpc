@extends("frontend.components.layout")
@section("title", "Events | Chroma By BPC")
@section("description", "Discover upcoming events at Chroma By BPC. Join us for exciting activities, workshops, and community gatherings.")
@section("keywords", "Chroma By BPC, events, workshops, community gatherings, activities")
@section("main")
<main class="main">

    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Registration</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li class="current">Registration</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Admissions Section -->
    <section id="admissions" class="admissions section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row mt-5 gy-4">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <div class="tuition-card">
              <h3 class="section-subtitle">Tuition &amp; Fees</h3>
              <div class="tuition-table">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Program</th>
                      <th>Registration Fee</th>
                      <th>Monthly Fee</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Undergraduate</td>
                      <td>$32,500</td>
                      <td>$2,800</td>
                    </tr>
                    <tr>
                      <td>Graduate</td>
                      <td>$38,700</td>
                      <td>$3,200</td>
                    </tr>
                    <tr>
                      <td>International</td>
                      <td>$42,300</td>
                      <td>$4,500</td>
                    </tr>
                    <tr>
                      <td>Online Programs</td>
                      <td>$26,400</td>
                      <td>$1,800</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              {{-- <div class="financial-aid">
                <h4>Financial Aid Available</h4>
                <p>Over 75% of our students receive some form of financial assistance. Merit scholarships range from $5,000 to full tuition.</p>
                <a href="#" class="btn btn-aid">Explore Financial Aid Options</a>
              </div> --}}
            </div>
          </div>

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
            <div class="contact-form-card">
              <h3 class="section-subtitle">Registration Form</h3>
              <form action="forms/contact.php" class="php-email-form">
                <div class="row g-3">
                  <div class="col-12">
                    <input type="text" name="name" class="form-control" placeholder="Name*" required="">
                  </div>
                  <div class="col-md-6">
                    <input type="email" name="email" class="form-control" placeholder="Email Address*" required="">
                  </div>
                  <div class="col-md-6">
                    <input type="tel" name="phone" class="form-control" placeholder="Phone Number">
                  </div>
                  <div class="col-12">
                    <select name="subject" class="form-select" required="">
                      <option selected="" disabled="">Program of Interest*</option>
                      <option>Undergraduate</option>
                      <option>Graduate</option>
                      <option>Doctoral</option>
                      <option>Certificate</option>
                      <option>Non-Degree</option>
                    </select>
                  </div>
                  <div class="col-12">
                    <textarea name="message" class="form-control" rows="7" placeholder="Questions or Comments"></textarea>
                  </div>
                  <div class="col-12">
                    <div class="loading">Loading</div>
                    <div class="error-message"></div>
                    <div class="sent-message">Your message has been sent. Thank you!</div>
                    <button type="submit" class="btn btn-request">Request Information</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Admissions Section -->

  </main>
@endsection