<?php

if (!function_exists('redirectWithAlert')) {
    function redirectWithAlert($message, $type = 'success', $redirectToRoute = null, $redirectTo = null, $extraData=[])
    {
        $responseData = [
            'alert' => [
                'icon' => $type,
                'text' => $message ?? ''
            ]
        ];
        if (request()->ajax() || request()->wantsJson()) {
            $extraData = array_merge(['status'=>$type, 'message'=>$message ?? ''], $extraData);
            return response()->json(array_merge($responseData, $extraData));
        }
        
        if (!is_null($redirectToRoute)) {
            return redirect()->route($redirectToRoute)->with($responseData);
        }

        if (!is_null($redirectTo)) {
            return redirect($redirectTo)->with($responseData);
        }
        return redirect()->back()->with($responseData);
    }
}
?>