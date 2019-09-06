<?php 
    use Yajra\Datatables\Datatables; 
    use App\Model\User\User;

    // get user auth
    $user = Auth::user();
?>

<div class="sidebar-wrapper">
<div class="logo">
    <a href="<?= URL::to('/'); ?>" class="simple-text">
        SIMPLE CRUD
    </a>
</div>

<ul class="nav">
    <li class="<?= $active == 'home' ? 'active' : '' ?>">
        <a href="<?= URL::to('/'); ?>">
            <i class="pe-7s-graph"></i>
            <p>Home</p>
        </a>
    </li>

    <li class="<?= $active == 'user' ? 'active' : '' ?>">
        <a href="<?= URL::to('/user'); ?>">
            <i class="pe-7s-user"></i>
            <p>Account</p>
        </a>
    </li>
    
</ul>

</div>

<style type="text/css">

.center 
{
    display: block;
    margin-left: auto;
    margin-right: auto;
}

</style>

@push('scripts')


@endpush