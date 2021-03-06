// ThumbnailMaker.cpp : Defines the entry point for the console application.
//

#include "stdafx.h"

using namespace Microsoft::WRL;

ComPtr<IWICImagingFactory> wicImagingFactory;
ComPtr<ID2D1Factory1> factory;

const UINT thumbnailWidth = 200;
const UINT thumbnailHeight = 150;

void VerifyHr(HRESULT hr)
{
	assert(SUCCEEDED(hr));
}

void Initialize()
{
	VerifyHr(CoInitialize(NULL));

	CoCreateInstance(
		CLSID_WICImagingFactory,
		NULL,
		CLSCTX_INPROC_SERVER,
		IID_IWICImagingFactory,
		(LPVOID*)&wicImagingFactory);

	D2D1_FACTORY_OPTIONS factoryOptions;
#ifdef DEBUG
	factoryOptions.debugLevel = D2D1_DEBUG_LEVEL_INFORMATION;
#else
	factoryOptions.debugLevel = D2D1_DEBUG_LEVEL_NONE;
#endif

	VerifyHr(D2D1CreateFactory<ID2D1Factory1>(D2D1_FACTORY_TYPE_SINGLE_THREADED, factoryOptions, &factory));
}

struct LoadedImage
{
	ComPtr<IWICFormatConverter> ImageData;
	UINT Width;
	UINT Height;
};

LoadedImage LoadImageFromFile(std::wstring const& fileName)
{
	HRESULT hr = S_OK;
	ComPtr<IWICBitmapDecoder> decoder;
	VerifyHr(wicImagingFactory->CreateDecoderFromFilename(
		fileName.c_str(),
		NULL,
		GENERIC_READ,
		WICDecodeMetadataCacheOnLoad, &decoder));

	if (!decoder)
	{
		// File is not an image file
		return LoadedImage{};
	}

	// Create the initial frame.
	ComPtr<IWICBitmapFrameDecode> source = NULL;
	VerifyHr(decoder->GetFrame(0, &source));

	WICPixelFormatGUID originalFormat;
	VerifyHr(source->GetPixelFormat(&originalFormat));

	// Convert the image format to 32bppPBGRA, equiv to DXGI_FORMAT_B8G8R8A8_UNORM
	ComPtr<IWICFormatConverter> converter;
	VerifyHr(wicImagingFactory->CreateFormatConverter(&converter));

	BOOL canConvertTo32bppPBGRA = false;
	VerifyHr(converter->CanConvert(originalFormat, GUID_WICPixelFormat32bppPBGRA, &canConvertTo32bppPBGRA));
	assert(canConvertTo32bppPBGRA);

	VerifyHr(converter->Initialize(
		source.Get(),
		GUID_WICPixelFormat32bppPBGRA,
		WICBitmapDitherTypeNone,
		NULL,
		0.f,
		WICBitmapPaletteTypeMedianCut
	));

	UINT width, height;
	VerifyHr(converter->GetSize(&width, &height));
	const UINT bpp = 4;
	const UINT pitch = bpp * width;

	LoadedImage result;
	result.ImageData = converter;
	result.Width = width;
	result.Height = height;
	return result;

}

ComPtr<IWICBitmap> ResizeLoadedImage(LoadedImage const& l)
{
	ComPtr<IWICBitmap> thumbnailWicBitmap;
	VerifyHr(wicImagingFactory->CreateBitmap(thumbnailWidth, thumbnailHeight, GUID_WICPixelFormat32bppPBGRA, WICBitmapCacheOnDemand, &thumbnailWicBitmap));

	ComPtr<ID2D1RenderTarget> renderTarget;
	D2D1_RENDER_TARGET_PROPERTIES d2dRenderTargetProperties = D2D1::RenderTargetProperties(D2D1_RENDER_TARGET_TYPE_DEFAULT, D2D1::PixelFormat(DXGI_FORMAT_B8G8R8A8_UNORM, D2D1_ALPHA_MODE_PREMULTIPLIED));
	VerifyHr(factory->CreateWicBitmapRenderTarget(thumbnailWicBitmap.Get(), d2dRenderTargetProperties, &renderTarget));

	ComPtr<ID2D1Bitmap> d2dSourceBitmap;
	D2D1_BITMAP_PROPERTIES bitmapProperties = D2D1::BitmapProperties(d2dRenderTargetProperties.pixelFormat);
	
	VerifyHr(renderTarget->CreateBitmapFromWicBitmap(l.ImageData.Get(), &d2dSourceBitmap));

	ComPtr<ID2D1DeviceContext> deviceContext;
	VerifyHr(renderTarget.As(&deviceContext));

	renderTarget->BeginDraw();
	deviceContext->DrawBitmap(d2dSourceBitmap.Get(), D2D1::RectF(thumbnailWidth, thumbnailHeight), 1.0f, D2D1_INTERPOLATION_MODE_HIGH_QUALITY_CUBIC);
	VerifyHr(renderTarget->EndDraw());

	return thumbnailWicBitmap;
}

bool SavePng(std::wstring destFilename, IWICBitmap* wicBitmap)
{
	ComPtr<IWICStream> stream;
	wicImagingFactory->CreateStream(&stream);
	VerifyHr(stream->InitializeFromFilename(destFilename.c_str(), GENERIC_WRITE));

	ComPtr<IWICBitmapEncoder> encoder;
	VerifyHr(wicImagingFactory->CreateEncoder(GUID_ContainerFormatJpeg, NULL, &encoder));

	VerifyHr(encoder->Initialize(stream.Get(), WICBitmapEncoderNoCache));

	ComPtr<IPropertyBag2> wicPropertyBag;
	ComPtr<IWICBitmapFrameEncode> frameEncode;
	VerifyHr(encoder->CreateNewFrame(&frameEncode, &wicPropertyBag));
	{
		PROPBAG2 option = { 0 };
		VARIANT varValue;
		option.pstrName = const_cast<LPOLESTR>(L"ImageQuality");
		VariantInit(&varValue);
		varValue.vt = VT_R4;
		varValue.fltVal = 0.65f;
		VerifyHr(wicPropertyBag->Write(1, &option, &varValue));
	}

	VerifyHr(frameEncode->Initialize(wicPropertyBag.Get()));
	VerifyHr(frameEncode->SetSize(thumbnailWidth, thumbnailHeight));
	frameEncode->SetResolution(96, 96);
	WICPixelFormatGUID pixelFormat = GUID_WICPixelFormat32bppPBGRA;
	VerifyHr(frameEncode->SetPixelFormat(&pixelFormat));

	HRESULT hr = (frameEncode->WriteSource(
		wicBitmap,
		NULL));
	VerifyHr(frameEncode->Commit());

	encoder->Commit();

	stream->Commit(STGC_DEFAULT);

	return true;
}

std::vector<std::wstring> GetTargetFiles(int argc, void** argv)
{
	std::string usageType = static_cast<char*>(argv[1]);
	std::vector<std::wstring> sourceFileNames;

	std::string arg = static_cast<char*>(argv[2]);
	std::wstring_convert<std::codecvt_utf8_utf16<wchar_t>> stringConverter;
	std::wstring formattedArg = stringConverter.from_bytes(arg);

	if (usageType == "-file")
	{
		sourceFileNames.push_back(formattedArg);
	}
	else if (usageType == "-folder")
	{
		std::wstring findTarget = formattedArg;
		findTarget.append(L"\\*");
		WIN32_FIND_DATA ffd;
		HANDLE hFind = FindFirstFile(findTarget.c_str(), &ffd);
		if (INVALID_HANDLE_VALUE == hFind)
		{
			return sourceFileNames;
		}

		do
		{
			if (!(ffd.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY))
			{
				std::wstring fileName = formattedArg;
				fileName.append(L"\\");
				fileName.append(ffd.cFileName);
				sourceFileNames.push_back(fileName);
			}
		} while (FindNextFile(hFind, &ffd) != 0);

		FindClose(hFind);
	}

	return sourceFileNames;
}

int main(int argc, void** argv)
{
	// Usage: ThumbnailMaker -file <filename>
	// or
	//		  ThumbnailMaker -folder <folder name>

	if (argc < 3)
		return -1;

	std::vector<std::wstring> sourceFileNames = GetTargetFiles(argc, argv);

	Initialize();

	for (size_t i = 0; i < sourceFileNames.size(); ++i)
	{
		std::wstring destFileName = sourceFileNames[i];
		destFileName.append(L".th.jpg");

		LoadedImage l = LoadImageFromFile(sourceFileNames[i]);

		if (l.ImageData)
		{
			ComPtr<IWICBitmap> thumbnailWicBitmap = ResizeLoadedImage(l);

			SavePng(destFileName, thumbnailWicBitmap.Get());
		}
	}

    return 0;
}

