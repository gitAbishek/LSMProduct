import {
	FormControl,
	FormErrorMessage,
	FormLabel,
	Input,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { Controller, useFormContext } from 'react-hook-form';
import Select from 'Components/common/Select';
import ReactSelect from 'react-select';

//@ts-ignore
const states = window._MASTERIYO_.states;
//@ts-ignore
const countries = window._MASTERIYO_.countries;
const countryOptions = Object.entries(countries).map(([code, name]) => ({
	value: code,
	label: name,
}));
const hasStates = (countryCode: string) => {
	return !!states[countryCode];
};
const getStateOptions = (countryCode: string) => {
	return Object.entries(states[countryCode]).map(([code, name]) => ({
		value: code,
		label: name,
	}));
};

interface Props {
	defaultCountry: string;
	defaultState?: string;
}
const CountryStateInputs: React.FC<Props> = (props) => {
	const { defaultCountry, defaultState } = props;
	const {
		register,
		formState: { errors },
		control,
		watch,
	} = useFormContext();
	// const [countryCode, setCountryCode] = useState<string>(defaultCountry);
	const billingCountry = watch('billing[country]');
	const countryCode = billingCountry ? billingCountry.value : defaultCountry;
	const isStatesAvailable = hasStates(countryCode);
	const billingState = watch('billing[state]');
	const stateCode = billingState ? billingState.value : defaultState;
	// console.log(stateCode);

	return (
		<Stack direction="row" spacing="8" py="3">
			<FormControl isInvalid={errors && errors['billing[country]']}>
				<FormLabel>{__('Country / Region', 'masteriyo')}</FormLabel>
				{/* <ReactSelect
					isSearchable
					options={countryOptions}
					{...register('billing[country]')}
					onChange={(value: any) => {}}
				/> */}
				<Controller
					defaultValue={
						defaultCountry && {
							label: countries[defaultCountry],
							value: defaultCountry,
						}
					}
					// defaultValue={countryCode}
					render={(args) => {
						const { field } = args;
						// console.log(field);
						return (
							<Select
								{...field}
								// defaultValue={
								// 	countryCode && {
								// 		label: countries[countryCode],
								// 		value: countryCode,
								// 	}
								// }
								isSearchable
								options={countryOptions}
								// onChange={(value: any) => setCountryCode(value.value)}
							/>
						);
					}}
					control={control}
					name="billing[country]"
				/>
				<FormErrorMessage>
					{errors &&
						errors['billing[country]'] &&
						errors['billing[country]'].message}
				</FormErrorMessage>
			</FormControl>
			<FormControl isInvalid={errors && errors['billing[state]']}>
				<FormLabel>{__('State / County', 'masteriyo')}</FormLabel>
				{true && (
					<Controller
						defaultValue={
							stateCode && {
								label: states[stateCode],
								value: stateCode,
							}
						}
						render={({ field }) => {
							if (isStatesAvailable) {
								console.log(field, states[countryCode][field.value.value]);
								return (
									<Select
										{...field}
										value={
											field.value.value && {
												label: states[field.value.value],
												value: field.value.value,
											}
										}
										isSearchable
										options={getStateOptions(countryCode)}
									/>
								);
							}
							return <Input {...field} value={field.value.value} />;
						}}
						control={control}
						name="billing[state]"
					/>
				)}
				{/* {!isStatesAvailable && (
							<Input {...register('billing[state]')} defaultValue={stateCode} />
				)} */}
				<FormErrorMessage>
					{errors &&
						errors['billing[state]'] &&
						errors['billing[state]'].message}
				</FormErrorMessage>
			</FormControl>
		</Stack>
	);
};

export default CountryStateInputs;
