<?php

namespace App\Services;

use App\Classes\Nestedsetbie;
use App\Repositories\ProductReceiptRepository;
use App\Repositories\RouterRepository;
use App\Services\Interfaces\ProductReceiptServiceInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class PostCatalogueService
 * @package App\Services
 */
class ProductReceiptService extends BaseService implements ProductReceiptServiceInterface
{
    protected $productReceiptRepository;
    protected $nestedset;
    protected $controllerName = 'ProductReceiptController';

    public function __construct(ProductReceiptRepository $productReceiptRepository, RouterRepository $routerRepository)
    {
        $this->productReceiptRepository = $productReceiptRepository;
        parent::__construct($routerRepository);
    }

    public function paginate($request, $languageId)
    {
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->input('publish') != null ? $request->integer('publish') : -1,
        ];

        $extend = ['path' => 'product/receipt/index'];
        return $this->productReceiptRepository->pagination($this->paginateSelect(), $condition, [], $perPage, $extend, []);
    }

    public function create($request, $languageId)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token', 'send');
            $payload['date_created'] = now();
            $payload['user_id'] = Auth::id();
            $payload['supplier_id'] = $request->input('supplier_id') != null ? $request->integer('supplier_id') : 0;
            $payload['total'] = $this->calculateTotal($payload);
            $productReceipt = $this->productReceiptRepository->create($payload);
            $productReceiptId = $productReceipt->id;
            $receiptDetailsPayload = $this->prepareReceiptDetailsPayload($request->all(), $productReceiptId);
            $this->addReceiptDetails($productReceipt, $receiptDetailsPayload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create product receipt.'
            ]);
        }
    }

    private function prepareReceiptDetailsPayload($requestData, $productReceiptId)
    {
        $receiptDetails = [];
        $quantities = $requestData['quantityReceipt'] ?? [];
        $prices = $requestData['price'] ?? [];
        $productIds = $requestData['productId'] ?? [];
        $variantIds = $requestData['productVariantId'] ?? [];

        for ($i = 0; $i < count($quantities); $i++) {
            $receiptDetails[] = [
                'product_receipt_id' => $productReceiptId,
                'product_id' => (int) $productIds[$i],
                'product_variant_id' => (int) $variantIds[$i],
                'quantity' => (int) $quantities[$i],
                'price' => convert_price($prices[$i]),
            ];
        }
        return $receiptDetails;
    }

    private function addReceiptDetails($productReceipt, array $receiptDetails)
    {
        foreach ($receiptDetails as $details) {
            $productReceipt->details()->create($details);
        }
    }

    private function calculateTotal($payload)
    {
        $total = 0;
        if (isset($payload['quantityReceipt']) && isset($payload['price'])) {
            for ($i = 0; $i < count($payload['quantityReceipt']); $i++) {
                $quantity = (float)$payload['quantityReceipt'][$i];
                $price = (float)$payload['price'][$i];
                $total += $quantity * $price;
            }
        }

        return $total;
    }

    public function update($id, $request, $languageId)
    {
        DB::beginTransaction();
        try {
            $productReceipt = $this->productReceiptRepository->findById($id);

            $payload = $request->except('_token', 'send');
            $payload['user_id'] = Auth::id();
            $payload['supplier_id'] = $request->input('supplier_id') != null ? $request->integer('supplier_id') : 0;
            $payload['total'] = $this->calculateTotal($payload);

            $productReceipt = $this->productReceiptRepository->update($id, $payload);
            $productReceiptId = $productReceipt->id;
            $productReceipt->details()->delete();

            $receiptDetailsPayload = $this->prepareReceiptDetailsPayload($request->all(), $productReceiptId);
            $this->addReceiptDetails($productReceipt, $receiptDetailsPayload);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function approve($id)
    {
        DB::beginTransaction();
        try {
            $payload['publish'] = 1;
            $this->productReceiptRepository->update($id, $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $this->productReceiptRepository->delete($id);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function paginateSelect()
    {
        return [
            'product_receipts.id',
            'product_receipts.publish',
            'product_receipts.user_id',
            'product_receipts.supplier_id',
            'product_receipts.total',
            'product_receipts.date_created'
        ];
    }
}
