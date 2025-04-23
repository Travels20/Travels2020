<?php
namespace Modules\Itinerary\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\AdminController;

class ItineraryController extends AdminController
{
    public function __construct()
    {

    }

    public function index()
    {
        return View('Itinerary::index');
    }
}
