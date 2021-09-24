import { Stack, Text } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef, useState } from 'react';
import DayPickerInput from 'react-day-picker/DayPickerInput';
import 'react-day-picker/lib/style.css';

interface Props {
	onChange: (from: string, to: string) => void;
}

const DateRangePicker: React.FC<Props> = (props) => {
	const { onChange } = props;
	const [from, setFrom] = useState<any>(undefined);
	const [to, setTo] = useState<any>(undefined);
	const toRef = useRef<any>();

	return (
		<Stack direction="row" align="center">
			<DayPickerInput
				value={from}
				placeholder={__('From', 'masteriyo')}
				dayPickerProps={{
					selectedDays: [from, { from, to }],
					disabledDays: { after: to },
					toMonth: to,
					modifiers: { start: from, end: to },
					numberOfMonths: 2,
					onDayClick: () => toRef.current?.getInput().focus(),
				}}
				onDayChange={(selectedFrom) => {
					setFrom(selectedFrom);

					if (selectedFrom && to) {
						onChange(selectedFrom.toISOString(), to.toISOString());
					}
				}}
			/>
			<Text> — </Text>
			<span className="masteriyo-orders-filter-daypicker-to">
				<DayPickerInput
					ref={toRef}
					value={to}
					placeholder={__('To', 'masteriyo')}
					dayPickerProps={{
						selectedDays: [from, { from, to }],
						disabledDays: { before: from },
						modifiers: { start: from, end: to },
						month: from,
						fromMonth: from,
						numberOfMonths: 2,
					}}
					onDayChange={(selectedTo) => {
						setTo(selectedTo);

						if (from && selectedTo) {
							onChange(from.toISOString(), selectedTo.toISOString());
						}
					}}
				/>
			</span>
		</Stack>
	);
};

export default DateRangePicker;
