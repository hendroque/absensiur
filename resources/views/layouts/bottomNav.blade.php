<!-- App Bottom Menu -->
<div class="appBottomMenu">
    <a href="/dashboard" class="item {{ request()-> is ('dashboard') ? 'active' : ''}}">
        <div class="col">
            <ion-icon name="home-outline" role="img" class="md hydrated"
                aria-label="file tray full outline"></ion-icon>
            <strong>Home</strong>
        </div>
    </a>
    <a href="/presensi/history" class="item {{ request()-> is ('presensi/history') ? 'active' : ''}}">
        <div class="col">
            <ion-icon name="document-text-outline" role="img" class="md hydrated"
                aria-label="history text outline"></ion-icon>
            <strong>History</strong>
        </div>
    </a>
    <a href="/presensi/create" class="item">
        <div class="col">
            <div class="action-button large">
                <ion-icon name="qr-code-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
            </div>
        </div>
    </a>
    <a href="/presensi/izin" class="item {{ request()-> is ('presensi/izin') ? 'active' : ''}}" >
        <div class="col">
            <ion-icon name="calendar-outline" role="img" class="md hydrated"
                aria-label="Form Izin outline"></ion-icon>
            <strong>Form Cuti</strong>
        </div>
    </a>
    <a href="/editprofile" class="item {{ request()-> is ('editprofile') ? 'active' : ''}}">
        <div class="col">
            <ion-icon name="people-outline" role="img" class="md hydrated" aria-label="Profile outline"></ion-icon>
            <strong>Profile</strong>
        </div>
    </a>
</div>
<!-- * App Bottom Menu -->