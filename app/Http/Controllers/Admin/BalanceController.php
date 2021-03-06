<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MoneyValidationFormRequest;
use App\Models\Historic;
use App\User;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    private $totalPage = 5;
    
    public function index()
    {
        $name = auth()->user()->name;
        $balance = auth()->user()->balance;
        $amount = $balance ? $balance->amount : 0;
        return view('admin.balance.index',compact('amount', 'name'));   
    }

    public function deposit()
    {
        return view('admin.balance.deposit');   
    }

    public function depositStore(MoneyValidationFormRequest $request)
    {
        
        $balance = auth()->user()->balance()->firstOrCreate([]);
        $response = $balance->deposit($request->value);
        if($response['success']):
            return redirect()
                ->route('admin.balance')
                ->with('success', $response['message']);
        endif;
        return redirect()
            ->back()
            ->with('error', $response['message']);
    }

    public function withdraw()
    {
        return view('admin.balance.withdraw');  
    }

    public function withdrawStore(MoneyValidationFormRequest $request)
    {
        $balance = auth()->user()->balance()->firstOrCreate([]);
        $response = $balance->withdraw($request->value);
        if($response['success']):
            return redirect()
                ->route('admin.balance')
                ->with('success', $response['message']);
        endif;
        return redirect()
            ->back()
            ->with('error', $response['message']);
    }

    public function transfer()
    {
        $name = auth()->user()->name;
        
        return view('admin.balance.transfer', compact('name'));
    }

    public function confirmTransfer(Request $request, User $user)
    {
        $name = auth()->user()->name;
        $balance = auth()->user()->balance;
        
        if(!$sender = $user->getSender($request->sender)):
            return redirect()
                   ->back()
                   ->with ('error', 'Nome ou e-mail não encontrado!');            
        endif;

        if ($sender->id === auth()->user()->id):
        return redirect()
                    ->back()
                    ->with('error', 'Não pode transferir para você mesmo!');
        endif;
      
        

        return view('admin.balance.transfer-confirm', compact('sender', 'name', 'balance'));
    }

    public function transferStore(MoneyValidationFormRequest $request, User $user)
    {
        if(!$sender = $user->find($request->sender_id)):
            return redirect()
                   ->route('balance.transfer')  
                   ->with ('error', 'Nome ou e-mail do destino não encontrado!');            
        endif;       
        
        $balance = auth()->user()->balance()->firstOrCreate([]);
        $response = $balance->transfer($request->value, $sender);
        if($response['success']):
            return redirect()
                ->route('admin.balance')
                ->with('success', $response['message']);
        endif;
        return redirect()
            ->back()
            ->with('error', $response['message']);
    }

    public function historic(Historic $historic)
    {
        $historics = auth()->user()->historics()->with(['userSender'])->paginate($this->totalPage);
        $name = auth()->user()->name;
        $types = $historic->type();
        
        return view('admin.balance.historics', compact('historics', 'name', 'types'));
    }

    public function searchHistoric(Request $request, Historic $historic)
    {
        $dataForm = $request->except('_token');

        $name = auth()->user()->name;

        $historics =  $historic->search($dataForm, $this->totalPage);

        $types = $historic->type();

        return view('admin.balance.historics', compact('historics', 'name', 'types', 'dataForm'));
    }
}
